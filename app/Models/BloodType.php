<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BloodType extends Model 
{

    protected $table = 'blood_types';
    public $timestamps = true;
    protected $fillable = array('name');

    public function blodClient()
    {
        return $this->belongsToMany('App\Model\Client');
    }

    public function blodClient()
    {
        return $this->hasMany('App\Model\Client');
    }

}