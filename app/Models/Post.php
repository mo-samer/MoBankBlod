<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Post extends Model 
{

    protected $table = 'posts';
    public $timestamps = true;
    protected $fillable = array('title', 'image', 'content', 'category_id');

    public function categoriesOfPosts()
    {
        return $this->belongsTo('App\Model\Category');
    }

    public function clientPost()
    {
        return $this->belongsToMany('App\Model\Client');
    }

}