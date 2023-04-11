<?php

namespace App\Helpers;

use App\Application;
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

    public static function getCandidateForBookedJobByApplicationID($applicationId){
        // dd($applicationId);
        $application = Application::find($applicationId);
        // dd($application);
        // $candidate = Candidate::find($application->candidate_id);
        // dd($candidate);

        $obj = [];
        $obj['id'] = $application->candidate->id;
        $obj['first_name'] = $application->candidate->first_name;
        $obj['last_name'] = $application->candidate->last_name;
        $obj['role'] = ApplicationStatusHelper::getCandidateCategoryByName($application->candidate->role);
        $obj['avatar'] = $application->candidate->avatar;
        $obj['application_id'] = $application->id;

        // dd($obj);
        return $obj;
    }

    public static function getBookedCandidateToTheJob($job){
        $application = Application::where(['job_id'=>$job->id])->whereIn('status',[2,3])->first();
        $canObj = [];
        if($application){
            $candidate = Candidate::find($application->candidate_id);
            $obj = [];
            $obj['id'] = $candidate->id;
            $obj['first_name'] = $candidate->first_name;
            $obj['last_name'] = $candidate->last_name;
            $obj['role'] = ApplicationStatusHelper::getCandidateCategoryByName($candidate->role);
            $obj['avatar'] = $candidate->avatar;
            $obj['application_id'] = $application->id;
            array_push($canObj,$obj);
            return $canObj;
            // dd($obj);
            // dd($obj);
        }
        else{
            return null;
        }
    }
    public static function getWorkedCandidateToTheJob($job){
        $application = Application::where(['job_id'=>$job->id, 'status'=>3])->first();
        $canObj = [];
        if($application){
            $candidate = Candidate::find($application->candidate_id);
            $obj = [];
            $obj['id'] = $candidate->id;
            $obj['first_name'] = $candidate->first_name;
            $obj['last_name'] = $candidate->last_name;
            $obj['role'] = ApplicationStatusHelper::getCandidateCategoryByName($candidate->role);
            $obj['avatar'] = $candidate->avatar;
            $obj['application_id'] = $application->id;
            array_push($canObj,$obj);
            return $canObj;
            // dd($obj);
            // dd($obj);
        }
        else{
            return null;
        }
    }
}