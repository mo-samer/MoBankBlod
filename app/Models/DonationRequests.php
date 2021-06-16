<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DonationRequests extends Model 
{

    protected $table = 'donation_requests';
    public $timestamps = true;
    protected $fillable = array('patien_name', 'patien_phone', 'city_id', 'hospital_name', 'blood_type_id', 'patien_age', 'bages_count', 'hospital_adress', 'latitude', 'longtude', 'client_id');

    public function clientesDonatinrequests()
    {
        return $this->belongsTo('App\Model\Client');
    }

    public function typeForDonation()
    {
        return $this->belongsTo('App\Model\BloodType');
    }

    public function donationWhereCity()
    {
        return $this->belongsTo('App\Model\City');
    }

    public function manyCitiy()
    {
        return $this->hasMany('App\Model\City');
    }

    public function requestNotification()
    {
        return $this->hasMany('App\Model\Notification');
    }

    public function manyTypes()
    {
        return $this->hasMany('App\Model\BloodType');
    }

}