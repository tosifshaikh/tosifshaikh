<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class menucategories extends Model
{
    protected $fillable = ['category_name','is_active'];
    protected $table = 'menucategories';
    public $timestamps = true;
}
