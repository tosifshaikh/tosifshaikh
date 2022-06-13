<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use HasFactory;
    protected $fillable = ['name','email','gender','image', 'bdate'];
    public $timestamps = true;


    public function setbdateAttribute($value)
    {
         $this->attributes['bdate']=\Carbon\Carbon::parse($value)->format('Y-m-d');
    } public function getbdateAttribute($value)
    {
        return $this->bdate =\Carbon\Carbon::parse($value)->format('d-m-Y');
    }
    /*  protected function bdate(): Attribute
    {
        return new Attribute(
            get: fn ($value) =>  Carbon::parse($value)->format('m/d/Y'),
            set: fn ($value) =>  Carbon::parse($value)->format('Y-m-d'),
        );
    } */
    public function hobby()
    {
      //  return $this->belongsToMany(hobby::class,'userDetail_id');
      return $this->hasMany(Hobby::class,'userDetail_id');
    }
}
