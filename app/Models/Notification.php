<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model 
{

    protected $table = 'notifications';
    public $timestamps = true;
    protected $fillable = array('title', 'content', 'donation_request_id');

    public function notificationsForRequests()
    {
        return $this->belongsTo('DonationRequests');
    }

    public function clientsNotification()
    {
        return $this->belongsToMany('App\Model\Client');
    }

}