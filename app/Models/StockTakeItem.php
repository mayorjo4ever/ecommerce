<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockTakeItem extends Model
{
    protected $fillable = [
        'stock_take_id', 'product_id',
        'system_quantity', 'physical_quantity',
        'variance', 'notes'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function stockTake()
    {
        return $this->belongsTo(StockTake::class);
    }
}