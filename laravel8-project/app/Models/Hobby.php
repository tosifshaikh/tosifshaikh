<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hobby extends Model
{
    use HasFactory;
    protected $fillable = ['userDetail_id','hobby_id'];
    public $timestamps = true;
    const HOBBIES= [1 => 'Cricket' ,2 => 'Football', 3 => 'Rugby'];

}
