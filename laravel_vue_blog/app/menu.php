<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class menu extends Model
{
    protected $fillable = ['pid','menucategories_id','menu_name','is_active'];
    public $timestamps = true;
}
