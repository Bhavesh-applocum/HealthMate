<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'avatar',
        'phone',
        'password',
        'role',
        'gender',   
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

    //protected $primaryKey = 'email';
    
    public function applications(){
        return $this->hasMany('App\Application');
    }

    public function timesheets(){
        return $this->hasOne('App\Timesheet');
    }

    public function jobs(){
        return $this->hasMany('App\Job');
    }

    public function invoices(){
        return $this->hasOne('App\Invoice');
    }
}
