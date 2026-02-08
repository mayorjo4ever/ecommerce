<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'subtotal',
        'tax',
        'shipping',
        'discount',
        'total',
        'status',
        'notes',
        'address_id',
        'qr_code',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();

        // ONLY generate order_number for Order model, not other models
        static::creating(function ($model) {
            // Make sure we're only operating on Order instances
            if ($model instanceof Order && !$model->order_number) {
                $model->order_number = 'ORD-' . strtoupper(uniqid());
            }
        });
    }

    /**
     * Get the user that owns the order
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the shipping address
     */
    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    /**
     * Get all order items
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get order items with product details
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class)->with('product');
    }

    /**
     * Get payment for this order
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'processing' => 'info',
            'shipped' => 'primary',
            'delivered' => 'success',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Check if order can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'processing']);
    }
}