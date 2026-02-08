<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'comment',
        'is_approved',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    /**
     * Get the product that owns the review
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user that wrote the review
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
