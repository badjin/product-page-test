<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'slug', 'price', 'active'];

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function discount()
    {
        return $this->hasOne(ProductDiscount::class);
    }

    public function getDiscountedPrice()
    {
        if (!$this->discount) {
            return $this->price;
        }

        if ($this->discount->type === 'percent') {
            return $this->price * (1 - $this->discount->amount / 100);
        } else {
            return $this->price - $this->discount->amount;
        }
    }
}
