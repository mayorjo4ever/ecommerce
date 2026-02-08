<?php

namespace App\Helpers;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class QRCodeHelper
{
    /**
     * Generate QR code for product
     */
    public static function generateProductQR($product)
    {
        $url = url('/products/' . $product->slug);
        $filename = 'qrcodes/product_' . $product->id . '_' . Str::random(8) . '.svg';
        
        $qrCode = QrCode::size(300)
            ->format('svg')
            ->generate($url);
        
        Storage::disk('public')->put($filename, $qrCode);
        
        return $filename;
    }

    /**
     * Generate QR code for order
     */
    public static function generateOrderQR($order)
    {
        $data = json_encode([
            'order_number' => $order->order_number,
            'total' => $order->total,
            'date' => $order->created_at->format('Y-m-d'),
        ]);
        
        $filename = 'qrcodes/order_' . $order->id . '_' . Str::random(8) . '.svg';
        
        $qrCode = QrCode::size(300)
            ->format('svg')
            ->generate($data);
        
        Storage::disk('public')->put($filename, $qrCode);
        
        return $filename;
    }

    /**
     * Delete QR code
     */
    public static function deleteQRCode($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            return true;
        }
        return false;
    }
}