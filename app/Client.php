<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'practice_name',
        'email',
        'avatar',
        'phone',
        'address',
        'password',
        'forgot_password_otp',
        'forgot_password_otp_expire'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'Login_otp',
        'Login_otp_expire',
        'forgot_password_otp',
        'forgot_password_otp_expire'
    ];

    public function jobs(){
        return $this->hasMany('App\Job');
    }

    public function timesheets(){
        return $this->hasMany('App\Timesheet');
    }

    public function candidates(){
        return $this->belongsToMany('App\Candidate');
    }

    public function applications(){
        return $this->hasMany('App\Application');
    }

    public function address(){
        return $this->hasMany('App\Address');
    }

    public function invoices(){
        return $this->hasMany('App\Invoice');
    }
}
