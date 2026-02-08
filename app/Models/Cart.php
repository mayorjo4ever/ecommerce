<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
     use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
    ];

    /**
     * Get the user that owns the cart
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all cart items
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get cart items with product details
     */
    public function items()
    {
        return $this->hasMany(CartItem::class)->with('product');
    }

    /**
     * Get cart total
     */
    public function getTotal()
    {
        return $this->items->sum(function ($item) {
            return $item->product->getCurrentPrice() * $item->quantity;
        });
    }

    /**
     * Get cart items count
     */
    public function getItemsCount()
    {
        return $this->items->sum('quantity');
    }
}
