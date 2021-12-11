<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ToDoListTask extends Model
{
    use SoftDeletes;
    protected $table = "tasks";
//    public function TaskCategory()
//    {
//      //  return $this->belongsTo(ToDoListCategory::class,'category_id','id');
//
//    }


}
