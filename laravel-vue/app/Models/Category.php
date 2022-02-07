<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = "categories";
    protected $fillable= [
        'name',
        'image',
    ];
    const PAGE = 2;
    const CATEGORY_PATH = 'assets'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'category'.DIRECTORY_SEPARATOR;

}
