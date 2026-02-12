<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class StockTake extends Model
{
    protected $fillable = [
        'reference', 'type', 'status',
        'period_start', 'period_end', 'notes',
        'created_by', 'completed_by', 'completed_at'
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'completed_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(StockTakeItem::class)->with('product');
    }

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function completedBy()
    {
        return $this->belongsTo(Admin::class, 'completed_by');
    }

    public function getTotalVarianceAttribute()
    {
        return $this->items->whereNotNull('variance')->sum('variance');
    }

    public function getDiscrepancyCountAttribute()
    {
        return $this->items->whereNotNull('variance')
            ->where('variance', '!=', 0)->count();
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'draft' => 'secondary',
            'in_progress' => 'warning',
            'completed' => 'success',
            default => 'secondary',
        };
    }
}