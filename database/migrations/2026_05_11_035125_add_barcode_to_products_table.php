<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Stores the raw value encoded inside the manufacturer's physical
     * QR code or barcode (EAN-13, UPC-A, GTIN, brand URL, etc.).
     *
     * This is separate from the system-generated qr_code (which stores
     * a path to our own QR image). The barcode column holds the TEXT
     * that a scanner reads off the physical product.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('barcode')->nullable()->unique()->after('sku')
                  ->comment('Raw value encoded in the physical product barcode/QR (EAN-13, UPC, GTIN, etc.)');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('barcode');
        });
    }
};