<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class menucategories extends Model
{
    use SoftDeletes;
    protected $fillable = ['category_name','is_active'];
    protected $table = 'menucategories';
    public $timestamps = true;

}
