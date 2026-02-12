<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'minimum_purchase',
        'usage_limit',
        'used_count',
        'valid_from',
        'valid_until',
        'is_active',
        'description',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'minimum_purchase' => 'decimal:2',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Check if coupon is valid
     */
    public function isValid($cartTotal = 0)
    {
        // Check if active
        if (!$this->is_active) {
            return ['valid' => false, 'message' => 'Coupon is inactive.'];
        }

        // Check date range
        if ($this->valid_from && Carbon::now()->lt($this->valid_from)) {
            return ['valid' => false, 'message' => 'Coupon is not yet valid.'];
        }

        if ($this->valid_until && Carbon::now()->gt($this->valid_until)) {
            return ['valid' => false, 'message' => 'Coupon has expired.'];
        }

        // Check usage limit
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return ['valid' => false, 'message' => 'Coupon usage limit reached.'];
        }

        // Check minimum purchase
        if ($this->minimum_purchase && $cartTotal < $this->minimum_purchase) {
            return [
                'valid' => false,
                'message' => 'Minimum purchase of ₦' . number_format($this->minimum_purchase, 2) . ' required.'
            ];
        }

        return ['valid' => true, 'message' => 'Coupon is valid.'];
    }

    /**
     * Calculate discount amount
     */
    public function calculateDiscount($cartTotal)
    {
        if ($this->type === 'percentage') {
            return round(($cartTotal * $this->value) / 100, 2);
        }

        return min($this->value, $cartTotal);
    }

    /**
     * Increment usage count
     */
    public function incrementUsage()
    {
        $this->increment('used_count');
    }

    /**
     * Get status
     */
    public function getStatusAttribute()
    {
        if (!$this->is_active) return 'inactive';
        if ($this->valid_until && Carbon::now()->gt($this->valid_until)) return 'expired';
        if ($this->valid_from && Carbon::now()->lt($this->valid_from)) return 'scheduled';
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return 'exhausted';
        return 'active';
    }

    /**
     * Get status color
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'active' => 'success',
            'inactive' => 'secondary',
            'expired' => 'danger',
            'scheduled' => 'info',
            'exhausted' => 'warning',
            default => 'secondary',
        };
    }

    /**
     * Get remaining uses
     */
    public function getRemainingUsesAttribute()
    {
        if (!$this->usage_limit) return 'Unlimited';
        return max(0, $this->usage_limit - $this->used_count);
    }
}