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
    const PRODUCT_PATH = 'assets'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'product'.DIRECTORY_SEPARATOR;
    const PAGE = 2;
    public function Category()
    {
        return $this->belongsTo(Category::class,'category_id','id');
       // return $this->belongsTo(Category::class);
    }
}
