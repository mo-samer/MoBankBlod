<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Governorate extends Model 
{

    protected $table = 'governorates';
    public $timestamps = true;
    protected $fillable = array('name');

    public function goverHasManyClient()
    {
        return $this->belongsToMany('App\Model\Client');
    }

    public function cityGover()
    {
        return $this->hasMany('App\Model\City');
    }

}