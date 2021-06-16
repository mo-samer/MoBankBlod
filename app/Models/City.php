<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class City extends Model 
{

    protected $table = 'cities';
    public $timestamps = true;
    protected $fillable = array('name', 'governorate_id');

    public function citiesOfGovernorate()
    {
        return $this->belongsTo('App\Model\Governorate');
    }

    public function clientCity()
    {
        return $this->hasMany('App\Model\Client');
    }

}