<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Client extends Model 
{

    protected $table = 'clients';
    public $timestamps = true;
    protected $fillable = array('phone', 'email', 'password', 'name', 'birth_date', 'blood_type_id', 'last_donation_date', 'city_id', 'pin_code');

    public function bloodType()
    {
        return $this->belongsTo('App\Model\BloodType');
    }

    public function clientsCity()
    {
        return $this->belongsTo('App\Model\City');
    }

    public function clientHasGover()
    {
        return $this->belongsToMany('App\Model\Governorate');
    }

    public function clientesBlood()
    {
        return $this->belongsToMany('App\Model\BloodType');
    }

    public function clientPost()
    {
        return $this->belongsToMany('App\Model\Post');
    }

    public function clientNotification()
    {
        return $this->belongsToMany('App\Model\Notification');
    }

}