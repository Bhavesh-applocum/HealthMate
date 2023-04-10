<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    public function job(){
        return $this->belongsTo('App\Job');
    }

    public function candidate(){
        return $this->belongsTo('App\Candidate');
    }

    public function timesheets(){
        return $this->hasOne('App\Timesheet');
    }

    public function invoices(){
        return $this->hasOne('App\Invoice');
    }
}
