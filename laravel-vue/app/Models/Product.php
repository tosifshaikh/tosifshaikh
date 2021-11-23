<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable= [
        'category_id',
        'product_name',
        'image',
    ];
    public $timestamps = true;
    public function Category()
    {
        return $this->belongsTo(Category::class,'category_id','id');
       // return $this->belongsTo(Category::class);
    }
}
