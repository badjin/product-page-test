<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductDiscount;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $product = Product::create([
            'name' => 'Fall Limited Edition Sneakers',
            'description' => "These low-profile sneakers are your perfect casual wear companion. Featuring a durable rubber outer sole, they'll withstand everything the weather can offer.",
            'price' => 250.00,
            'slug' => 'fall-limited-edition-sneakers',
            'active' => true,
        ]);

        // Add images
        $imageUrls = [
            '/images/image-product-1.jpg',
            '/images/image-product-2.jpg',
            '/images/image-product-3.jpg',
            '/images/image-product-4.jpg',
        ];

        foreach ($imageUrls as $url) {
            ProductImage::create([
                'product_id' => $product->id,
                'path' => $url,
            ]);
        }

        // Add discount
        ProductDiscount::create([
            'product_id' => $product->id,
            'type' => 'percent',
            'amount' => 50,
        ]);
    }
}