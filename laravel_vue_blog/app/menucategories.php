<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class menucategories extends Model
{
    protected $fillable = ['category_name','is_active'];
    public $timestamps = true;
}
