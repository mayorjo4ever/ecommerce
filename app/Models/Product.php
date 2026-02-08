<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'description',
        'short_description',
        'price',
        'sale_price',
        'quantity',
        'category_id',
        'featured_image',
        'is_featured',
        'is_active',
        'qr_code',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    // NO boot() method should be here for Product model

    /**
     * Get the category that owns the product
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get all images for the product
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('order');
    }

    /**
     * Get all reviews for the product
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get approved reviews only
     */
    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    /**
     * Get order items
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get cart items
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Check if product is on sale
     */
    public function isOnSale(): bool
    {
        return $this->sale_price && $this->sale_price < $this->price;
    }

    /**
     * Get the current price (sale price if available, otherwise regular price)
     */
    public function getCurrentPrice()
    {
        return $this->isOnSale() ? $this->sale_price : $this->price;
    }

    /**
     * Check if product is in stock
     */
    public function inStock(): bool
    {
        return $this->quantity > 0;
    }

    /**
     * Get average rating
     */
    public function getAverageRatingAttribute()
    {
        return $this->approvedReviews()->avg('rating') ?? 0;
    }

    /**
     * Get total reviews count
     */
    public function getTotalReviewsAttribute()
    {
        return $this->approvedReviews()->count();
    }

    /**
     * Get discount percentage
     */
    public function getDiscountPercentageAttribute()
    {
        if ($this->isOnSale()) {
            return round((($this->price - $this->sale_price) / $this->price) * 100);
        }
        return 0;
    }
}