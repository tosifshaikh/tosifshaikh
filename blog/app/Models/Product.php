<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable= [
        'cat_id',
        'product_name',
        'slug',
        'small_description',
        'product_description',
        'original_price',
        'selling_price',
        'image',
        'qty',
        'tax',
        'status',
        'trending',
        'meta_title',
        'meta_keywords',
        'meta_description',
    ];
    public function Category()
    {
        return $this->belongsTo(Category::class,'cat_id','id');
    }
}
