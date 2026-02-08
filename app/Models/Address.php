<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{ 
    use HasFactory;
    
     protected $fillable = [
        'user_id',
        'full_name',
        'phone',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
        'country',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * Get the user that owns the address
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get orders with this address
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get full address as string
     */
    public function getFullAddressAttribute()
    {
        $parts = [
            $this->address_line_1,
            $this->address_line_2,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country,
        ];

        return implode(', ', array_filter($parts));
    }
}
