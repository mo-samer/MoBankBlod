<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ClienPost extends Model 
{

    protected $table = 'client_post';
    public $timestamps = true;
    protected $fillable = array('client_id', 'post_id');

}