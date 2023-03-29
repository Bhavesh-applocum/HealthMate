<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    public function job(){
        return $this->belongsTo('App\Job');
    }

    public function candidate(){
        return $this->belongsToMany('App\Candidate');
    }

    public function timesheet(){
        return $this->hasOne('App\Timesheet');
    }
}
