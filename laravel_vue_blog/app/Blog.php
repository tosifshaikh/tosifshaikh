<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    protected $fillable = ['title','post','post_excerpt','slug','user_id','featuredImage','meta_description','views','jsonData'];
    public function setSlugAttribute($title)
    {
        $this->attributes['slug'] = $this->uniqueSlug($title);
    }
    private function uniqueSlug($title)
    {
       $slug = Str::slug($title,'-');
       $count = Blog::where('slug','like',"{$slug}%")->count();
       $newCount = $count > 0 ? ++$count : 0;
       return $newCount > 0 ? "$slug-$newCount" : $slug;
    }
    public function tag()
    {
        return $this->belongsToMany('App\Tag','blogtags');
    }
    public function cat()
    {
        return $this->belongsToMany('App\Category','blogcategories');
    }
}
