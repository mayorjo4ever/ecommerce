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
        'amount_paid',
        'balance',
        'status',
        'payment_status',
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
        'amount_paid' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (!$order->order_number) {
                $order->order_number = 'ORD-' . strtoupper(uniqid());
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class)->with('product');
    }

    // Changed from hasOne to hasMany to support multiple payments
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Keep this for backward compatibility
    public function payment()
    {
        return $this->hasOne(Payment::class)->latest();
    }

    /**
     * Get total amount paid from all payments
     */
    public function getTotalPaid()
    {
        return $this->payments()->where('status', 'completed')->sum('amount');
    }

    /**
     * Get remaining balance
     */
    public function getRemainingBalance()
    {
        return $this->total - $this->getTotalPaid();
    }

    /**
     * Update payment status based on payments
     */
    public function updatePaymentStatus()
    {
        $totalPaid = $this->getTotalPaid();
        
        if ($totalPaid == 0) {
            $this->payment_status = 'unpaid';
        } elseif ($totalPaid < $this->total) {
            $this->payment_status = 'partial';
        } else {
            $this->payment_status = 'paid';
        }
        
        $this->amount_paid = $totalPaid;
        $this->balance = $this->total - $totalPaid;
        $this->save();
    }

    /**
     * Check if order is fully paid
     */
    public function isFullyPaid()
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Check if order has partial payment
     */
    public function hasPartialPayment()
    {
        return $this->payment_status === 'partial';
    }

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

    public function getPaymentStatusColorAttribute()
    {
        return match($this->payment_status) {
            'unpaid' => 'danger',
            'partial' => 'warning',
            'paid' => 'success',
            'refunded' => 'secondary',
            default => 'secondary',
        };
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'processing']);
    }
}