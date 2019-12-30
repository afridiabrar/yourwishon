<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    protected $fillable = ['name', 'slug', 'icon', 'hover_icon'];



    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }
}
