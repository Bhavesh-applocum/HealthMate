<?php

namespace App\Helpers;

use App\Candidate;

class JobHelper {
    public static function getApplicationIDsfromJob($job){
        $applications = $job->applications;
        $applicationIds = [];
        foreach($applications as $application){
            array_push($applicationIds, $application->id);
        }
        return $applicationIds;
    }
    public static function getCandidatesIds($candidates){
        $candidatesIds = [];
        foreach($candidates as $candidate){
            array_push($candidatesIds, $candidate->id);
        }
        return $candidatesIds;
    }
    public static function getCandidatesIDSFromJob($job){
        $candidatesIds = [];
        foreach($job->applications as $j){
            array_push($candidatesIds,$j->candidate_id);
        }
        return $candidatesIds;
    }
    public static function getCandidateDataForJob($job){
        $candidates = [];
        foreach($job->applications as $j){
            $candidate = Candidate::find($j->candidate_id);
            $obj = [];
            $obj['id'] = $candidate->id;
            $obj['first_name'] = $candidate->first_name;
            $obj['last_name'] = $candidate->last_name;
            $obj['email'] = $candidate->email;
            $obj['phone'] = $candidate->phone;
            $obj['role'] = ApplicationStatusHelper::getCandidateCategoryByName($candidate->role);
            $obj['avatar'] = $candidate->avatar;
            $obj['application_id'] = $j->id;
            array_push($candidates,$obj);
        }
        return $candidates;
    }
}