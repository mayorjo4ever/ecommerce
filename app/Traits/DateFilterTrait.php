<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Carbon\Carbon;

trait DateFilterTrait
{
    /**
     * Apply date range filter to query
     */
    public function applyDateFilter($query, Request $request, $column = 'created_at')
    {
        if ($request->filled('date_from')) {
            $query->whereDate($column, '>=', Carbon::parse($request->date_from)->startOfDay());
        }

        if ($request->filled('date_to')) {
            $query->whereDate($column, '<=', Carbon::parse($request->date_to)->endOfDay());
        }

        return $query;
    }

    /**
     * Get summary statistics for filtered results
     */
    public function getFilterSummary($query)
    {
        return [
            'total_orders' => $query->count(),
            'total_revenue' => $query->sum('total'),
            'total_paid' => $query->sum('amount_paid'),
            'total_balance' => $query->sum('balance'),
        ];
    }
}