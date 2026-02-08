<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create some test users
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'name' => "Customer $i",
                'email' => "customer$i@test.com",
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);
        }

        // Create some categories
        $categories = [
            'Electronics',
            'Clothing',
            'Books',
            'Home & Garden',
            'Sports',
        ];

        foreach ($categories as $catName) {
            Category::create([
                'name' => $catName,
                'slug' => Str::slug($catName),
                'description' => "Description for $catName",
                'is_active' => true,
            ]);
        }

        // Create some products
        for ($i = 1; $i <= 20; $i++) {
            Product::create([
                'name' => "Product $i",
                'slug' => "product-$i",
                'sku' => 'SKU' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'description' => "Description for Product $i",
                'short_description' => "Short description for Product $i",
                'price' => rand(1000, 50000),
                'sale_price' => rand(500, 45000),
                'quantity' => rand(0, 100),
                'category_id' => rand(1, 5),
                'is_featured' => rand(0, 1),
                'is_active' => true,
            ]);
        }

        // Create some orders
        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        
        for ($i = 1; $i <= 30; $i++) {
            $subtotal = rand(5000, 100000);
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'user_id' => rand(1, 10),
                'subtotal' => $subtotal,
                'tax' => $subtotal * 0.075,
                'shipping' => 1500,
                'discount' => 0,
                'total' => $subtotal + ($subtotal * 0.075) + 1500,
                'status' => $statuses[array_rand($statuses)],
                'created_at' => now()->subDays(rand(0, 30)),
            ]);

            // Create order items
            for ($j = 1; $j <= rand(1, 5); $j++) {
                $product = Product::find(rand(1, 20));
                $quantity = rand(1, 5);
                $price = $product->getCurrentPrice();
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $price * $quantity,
                ]);
            }
        }

        $this->command->info('Test data created successfully!');
    }
}