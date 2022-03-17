<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = ['title','post','post_excerpt','slug','user_id','featuredImage','metaDescription','views'];
}
