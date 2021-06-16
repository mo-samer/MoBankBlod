<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model 
{

    protected $table = 'contact_us';
    public $timestamps = true;
    protected $fillable = array('message', 'title');

}