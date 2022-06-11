<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use HasFactory;
    protected $fillable = ['name','email','gender','hobby_id','image'];
    public $timestamp = true;

    public function hobby()
    {
        return $this->belongsTo('App\Models\Hobby','userDetail_id');
    }
    public function setbdateAttribute($value)
    {
         $this->attributes['bdate']=\Carbon\Carbon::parse($value)->format('Y-m-d');
    } public function getbdateAttribute($value)
    {
         $this->attributes['bdate']=\Carbon\Carbon::parse($value)->format('D-m-Y');
    }
}
