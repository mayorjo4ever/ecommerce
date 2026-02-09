<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\QRCodeHelper;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use function asset;
use function auth;
use function GuzzleHttp\json_encode;
use function response;
use function Symfony\Component\Clock\now;
use function view;

class POSController extends Controller
{
    /**
     * Display POS interface
     */
    public function index()
    {
        Session::put('page','pos');
        
        $products = Product::where('is_active', true)
            ->where('quantity', '>', 0)
            ->with('category')
            ->latest()
            ->paginate(12);
        
        return view('admin.pos.index', compact('products'));
    }

    /**
     * Search products for POS
     */
    public function searchProducts(Request $request)
    {
        $search = $request->input('search');
        
        $products = Product::where('is_active', true)
            ->where('quantity', '>', 0)
            ->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%");
            })
            ->with('category')
            ->limit(20)
            ->get();
        
        return response()->json($products);
    }

    /**
     * Get product details
     */
    public function getProduct($id)
    {
        $product = Product::with('category')->find($id);
        
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        
        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'price' => $product->getCurrentPrice(),
            'regular_price' => $product->price,
            'sale_price' => $product->sale_price,
            'quantity' => $product->quantity,
            'category' => $product->category->name ?? 'N/A',
            'image' => $product->featured_image ? asset('storage/' . $product->featured_image) : null,
        ]);
    }

    /**
     * Search or create customer
     */
    public function searchCustomer(Request $request)
    {
        $search = $request->input('search');
        
        $customers = User::where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get();
        
        return response()->json($customers);
    }

    /**
     * Create quick customer (walk-in)
     */
    public function createQuickCustomer(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:users,email',
        ]);

        // Generate random email if not provided
        if (!isset($validated['email'])) {
            $validated['email'] = 'customer_' . time() . '@walkin.local';
        }

        // Generate random password
        $validated['password'] = Hash::make('password123');
        $validated['email_verified_at'] = now();

        $customer = User::create($validated);

        return response()->json($customer);
    }

    /**
     * Process POS sale
     */
    public function processSale(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:users,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'payment_method' => 'required|string|in:cash,card,transfer,pos',
            'amount_paid' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Calculate totals
            $subtotal = 0;
            foreach ($validated['items'] as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }

            $discount = $validated['discount'] ?? 0;
            $tax = $validated['tax'] ?? 0;
            $total = $subtotal - $discount + $tax;

            // Check if amount paid is sufficient
            if ($validated['amount_paid'] < $total) {
                return response()->json([
                    'error' => 'Insufficient payment. Amount paid is less than total.'
                ], 422);
            }

            // Create order
            $order = Order::create([
                'order_number' => 'POS-' . strtoupper(uniqid()),
                'user_id' => $validated['customer_id'],
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping' => 0,
                'discount' => $discount,
                'total' => $total,
                'status' => 'delivered', // POS sales are instant
                'notes' => $validated['notes'] ?? 'Point of Sale Transaction',
            ]);

            // Create order items and update inventory
            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);
                
                // Check stock availability
                if ($product->quantity < $item['quantity']) {
                    DB::rollBack();
                    return response()->json([
                        'error' => "Insufficient stock for {$product->name}. Available: {$product->quantity}"
                    ], 422);
                }

                // Create order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['price'] * $item['quantity'],
                ]);

                // Update product quantity
                $product->decrement('quantity', $item['quantity']);
            }

            // Create payment record
            Payment::create([
                'order_id' => $order->id,
                'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                'payment_method' => $validated['payment_method'],
                'amount' => $validated['amount_paid'],
                'status' => 'completed',
                'payment_details' => json_encode([
                    'amount_paid' => $validated['amount_paid'],
                    'change' => $validated['amount_paid'] - $total,
                    'processed_by' => auth()->guard('admin')->user()->name,
                    'processed_at' => now(),
                ]),
            ]);

            // Generate QR code for order
            $order->qr_code = QRCodeHelper::generateOrderQR($order);
            $order->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sale completed successfully!',
                'order' => $order->load(['orderItems.product', 'user', 'payment']),
                'change' => $validated['amount_paid'] - $total,
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to process sale: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Print receipt
     */
    public function printReceipt($orderId)
    {
        $order = Order::with(['orderItems.product', 'user', 'payment'])
            ->findOrFail($orderId);
        
        return view('admin.pos.receipt', compact('order'));
    }

    /**
     * Get POS sales history
     */
    public function salesHistory()
    {
        $sales = Order::where('order_number', 'like', 'POS-%')
            ->with(['user', 'payment'])
            ->latest()
            ->paginate(20);
        
        return view('admin.pos.history', compact('sales'));
    }
    
    /**
 * Search product by QR code
 */
public function searchByQRCode(Request $request)
{
    $qrCode = $request->input('qr_code');
    
    // QR code contains the product URL, so we need to extract the slug
    // Format: http://yoursite.com/products/product-slug
    $slug = basename(parse_url($qrCode, PHP_URL_PATH));
    
    $product = Product::where('slug', $slug)
        ->orWhere('sku', $qrCode)
        ->where('is_active', true)
        ->where('quantity', '>', 0)
        ->first();
    
    if (!$product) {
        return response()->json(['error' => 'Product not found'], 404);
    }
    
    return response()->json([
        'id' => $product->id,
        'name' => $product->name,
        'sku' => $product->sku,
        'price' => $product->getCurrentPrice(),
        'regular_price' => $product->price,
        'sale_price' => $product->sale_price,
        'quantity' => $product->quantity,
        'category' => $product->category->name ?? 'N/A',
        'image' => $product->featured_image ? asset('storage/' . $product->featured_image) : null,
    ]);
}
}
