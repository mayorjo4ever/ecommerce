<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\QRCodeHelper;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
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
            'payments' => 'required|array|min:1', // Changed to support multiple payments
            'payments.*.method' => 'required|string|in:cash,card,transfer,pos',
            'payments.*.amount' => 'required|numeric|min:0',
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

            // Calculate total amount being paid
            $totalPaying = array_sum(array_column($validated['payments'], 'amount'));

            // Determine order and payment status
            if ($totalPaying >= $total) {
                $orderStatus = 'delivered'; // Full payment - instant delivery for POS
                $paymentStatus = 'paid';
            } else {
                $orderStatus = 'pending'; // Partial payment - pending
                $paymentStatus = 'partial';
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
                'amount_paid' => $totalPaying,
                'balance' => $total - $totalPaying,
                'status' => $orderStatus,
                'payment_status' => $paymentStatus,
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

            // Create payment records for each payment method
            foreach ($validated['payments'] as $paymentData) {
                Payment::create([
                    'order_id' => $order->id,
                    'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                    'payment_method' => $paymentData['method'],
                    'amount' => $paymentData['amount'],
                    'status' => 'completed',
                    'payment_details' => json_encode([
                        'processed_by' => auth()->guard('admin')->user()->name,
                        'processed_at' => now(),
                        'payment_type' => $paymentData['method'],
                    ]),
                ]);
            }

            // Generate QR code for order
            $order->qr_code = QRCodeHelper::generateOrderQR($order);
            $order->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $paymentStatus === 'paid' 
                    ? 'Sale completed successfully!' 
                    : 'Partial payment recorded. Balance: ₦' . number_format($order->balance, 2),
                'order' => $order->load(['orderItems.product', 'user', 'payments']),
                'total_paid' => $totalPaying,
                'balance' => $order->balance,
                'change' => $totalPaying > $total ? $totalPaying - $total : 0,
                'payment_status' => $paymentStatus,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to process sale: ' . $e->getMessage()
            ], 500);
        }
    }

    
    /**
        * Record additional payment for partial payment order
        */
       public function recordPayment(Request $request, Order $order)
       {
           $validated = $request->validate([
               'payments' => 'required|array|min:1',
               'payments.*.method' => 'required|string|in:cash,card,transfer,pos',
               'payments.*.amount' => 'required|numeric|min:0',
           ]);

           try {
               DB::beginTransaction();

               $totalPaying = array_sum(array_column($validated['payments'], 'amount'));

               // Check if payment exceeds balance
               if ($totalPaying > $order->balance) {
                   return response()->json([
                       'error' => 'Payment amount exceeds balance. Balance: ₦' . number_format($order->balance, 2)
                   ], 422);
               }

               // Create payment records
               foreach ($validated['payments'] as $paymentData) {
                   Payment::create([
                       'order_id' => $order->id,
                       'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                       'payment_method' => $paymentData['method'],
                       'amount' => $paymentData['amount'],
                       'status' => 'completed',
                       'payment_details' => json_encode([
                           'processed_by' => auth()->guard('admin')->user()->name,
                           'processed_at' => now(),
                           'payment_type' => $paymentData['method'],
                           'note' => 'Additional payment for partial payment order',
                       ]),
                   ]);
               }

               // Update order payment status
               $order->updatePaymentStatus();

               // If fully paid and still pending, mark as delivered
               if ($order->isFullyPaid() && $order->status === 'pending') {
                   $order->status = 'delivered';
                   $order->save();
               }

               DB::commit();

               return response()->json([
                   'success' => true,
                   'message' => $order->isFullyPaid() 
                       ? 'Payment completed successfully!' 
                       : 'Payment recorded. Remaining balance: ₦' . number_format($order->balance, 2),
                   'order' => $order->load(['orderItems.product', 'user', 'payments']),
                   'remaining_balance' => $order->balance,
               ]);

           } catch (\Exception $e) {
               DB::rollBack();
               return response()->json([
                   'error' => 'Failed to record payment: ' . $e->getMessage()
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
    public function salesHistory(Request $request)
    {
        $query = Order::where('order_number', 'like', 'POS-%')
            ->with(['user', 'payments', 'orderItems'])
            ->latest();

        // Apply date filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->date_from)->startOfDay());
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->date_to)->endOfDay());
        }

        // Apply payment status filter
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Apply payment method filter
        if ($request->filled('payment_method')) {
            $query->whereHas('payments', function($q) use ($request) {
                $q->where('payment_method', $request->payment_method);
            });
        }

        // Apply search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        // Get summary before pagination
        $summaryQuery = clone $query;
        $summary = [
            'total_sales' => $summaryQuery->count(),
            'total_revenue' => $summaryQuery->sum('total'),
            'total_paid' => $summaryQuery->sum('amount_paid'),
            'total_balance' => $summaryQuery->sum('balance'),
            'cash_sales' => Order::where('order_number', 'like', 'POS-%')
                ->whereHas('payments', fn($q) => $q->where('payment_method', 'cash'))
                ->when($request->filled('date_from'), fn($q) => $q->whereDate('created_at', '>=', $request->date_from))
                ->when($request->filled('date_to'), fn($q) => $q->whereDate('created_at', '<=', $request->date_to))
                ->sum('total'),
            'transfer_sales' => Order::where('order_number', 'like', 'POS-%')
                ->whereHas('payments', fn($q) => $q->where('payment_method', 'transfer'))
                ->when($request->filled('date_from'), fn($q) => $q->whereDate('created_at', '>=', $request->date_from))
                ->when($request->filled('date_to'), fn($q) => $q->whereDate('created_at', '<=', $request->date_to))
                ->sum('total'),
        ];

        $sales = $query->paginate(20)->withQueryString();

        return view('admin.pos.history', compact('sales', 'summary'));
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
        
    public function exportHistory(Request $request)
    {
        $query = Order::where('order_number', 'like', 'POS-%')
            ->with(['user', 'payments', 'orderItems'])
            ->latest();

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->date_from));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->date_to));
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $sales = $query->get();
        $filename = 'pos_sales_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($sales) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Order Number',
                'Customer',
                'Phone',
                'Items',
                'Payment Methods',
                'Total',
                'Amount Paid',
                'Balance',
                'Payment Status',
                'Date',
                'Time',
                'Processed By',
            ]);

            foreach ($sales as $sale) {
                $paymentMethods = $sale->payments->map(function($p) {
                    return strtoupper($p->payment_method) . ': ₦' . number_format($p->amount, 2);
                })->implode(' | ');

                $details = json_decode($sale->payments->first()?->payment_details, true);

                fputcsv($file, [
                    $sale->order_number,
                    $sale->user->name ?? 'N/A',
                    $sale->user->phone ?? 'N/A',
                    $sale->orderItems->sum('quantity'),
                    $paymentMethods,
                    $sale->total,
                    $sale->amount_paid,
                    $sale->balance,
                    ucfirst($sale->payment_status),
                    $sale->created_at->format('d M Y'),
                    $sale->created_at->format('H:i:s'),
                    $details['processed_by'] ?? 'N/A',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    
}
