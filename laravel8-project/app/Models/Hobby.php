<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hobby extends Model
{
    use HasFactory;
    protected $fillable = ['userDetail_id','hobby_id'];
    public $timestamps = true;
    public function hobby()
    {
      //  return $this->belongsToMany(hobby::class,'userDetail_id');
      return $this->belongsToMany(UserDetail::class);
    }
}
