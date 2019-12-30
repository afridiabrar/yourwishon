<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    //
    protected $fillable = ['product_id', 'order_id', 'qty', 'price', 'tax_amount', 'total_amount'];

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function product_image()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'product_id');
    }
}
