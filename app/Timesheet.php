<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Timesheet extends Model
{
    protected $fillable = [
        'candidate_id',
        'job_id',
        'start_time',
        'end_time',
        'break_time',
        'status'
    ];

    public function candidate()
    {
        return $this->belongsTo('App\Candidate');
    }

    public function job()
    {
        return $this->belongsTo('App\Job');
    }
}
