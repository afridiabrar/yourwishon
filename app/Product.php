<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $fillable = ['name', 'label', 'slug', 'size', 'category_id', 'description', 'in_stock', 'track_stock', 'color', 'qty', 'is_taxable', 'price', 'cost_price', 'weight', 'width', 'height', 'length', 'featured_image', 'is_featured', 'status'];


    public function categories()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
    public function productImages()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }
}
