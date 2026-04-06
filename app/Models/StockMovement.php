<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    protected $fillable = [
        'product_id',
        'supplier_name',
        'created_by',
        'type',
        'quantity',
        'quantity_before',
        'quantity_after',
        'cost_price',
        'reference_no',
        'note',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
    ];

    // ==================== RELATIONSHIPS ====================

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    // ==================== HELPERS ====================

    /**
     * Human-readable movement type label
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'in'         => 'Stock Received',
            'out'        => 'Stock Removed',
            'adjustment' => 'Adjustment',
            'return'     => 'Return',
            'damaged'    => 'Damaged / Loss',
            default      => ucfirst($this->type),
        };
    }

    /**
     * Bootstrap colour for each type
     */
    public function getTypeBadgeAttribute(): string
    {
        return match($this->type) {
            'in'         => 'success',
            'out'        => 'danger',
            'adjustment' => 'warning',
            'return'     => 'info',
            'damaged'    => 'dark',
            default      => 'secondary',
        };
    }

    /**
     * Whether this movement increases stock
     */
    public function isIncoming(): bool
    {
        return in_array($this->type, ['in', 'return']);
    }
}