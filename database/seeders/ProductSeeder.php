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
        // Array of free clothing image URLs
        $imageUrls = [
            'https://pixabay.com/photos/clothesline-little-girl-dresses-804812/',
            'https://pixabay.com/photos/white-clothing-people-girl-2565592/',
            'https://pixabay.com/illustrations/ai-generated-man-beach-apparel-8786355/',
            'https://pixabay.com/photos/girl-blonde-winter-clothes-face-1039729/',
            'https://pixabay.com/photos/sweatshirts-sweaters-exhibition-428607/',
            'https://pixabay.com/photos/shirts-exhibition-store-shopping-428627/',
            'https://pixabay.com/photos/shirts-exhibition-store-shopping-428618/',
            'https://pixabay.com/photos/clothes-sneakers-shoes-fedora-hat-922988/',
            'https://pixabay.com/photos/fashion-suit-tailor-clothes-1979136/',
            'https://pixabay.com/photos/wardrobe-coat-hanger-dressing-room-5961193/',
        ];

        Product::factory()->count(5)->create()->each(function ($product, $index) use ($imageUrls) {
            // Add images
            $productImages = array_slice($imageUrls, 0, 2);
            foreach ($productImages as $url) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $url,
                ]);
            }

            // Apply 30% discount to even-indexed products
            if ($index % 2 == 0) {
                ProductDiscount::create([
                    'product_id' => $product->id,
                    'type' => 'percent',
                    'discount' => 30,
                ]);
            }
        });
    }
}