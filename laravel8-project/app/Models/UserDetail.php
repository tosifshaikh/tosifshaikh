<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use HasFactory;
    protected $fillable = ['name','email','gender','image'];
    public $timestamps = true;


    public function setbdateAttribute($value)
    {
         $this->attributes['bdate']=\Carbon\Carbon::parse($value)->format('Y-m-d');
    } public function getbdateAttribute($value)
    {
         $this->attributes['bdate']=\Carbon\Carbon::parse($value)->format('d-m-Y');
    }
}
