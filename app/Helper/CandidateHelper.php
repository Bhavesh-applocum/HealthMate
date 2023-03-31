<?php

namespace App\Helpers;

use App\Candidate;

class CandidateHelper {
    public static function getAvatarFromCandidateId($candidateId){
        $candidate = Candidate::find($candidateId);
        return $candidate->avatar ?? "";
    }
    public static function getFullName($can){
        $name = $can->first_name." ".$can->last_name;
        return $name;
    }
    public static function getCandidateField($candidateId,$field){
        $candidate = Candidate::find($candidateId);
        $obj = [];
        foreach($field as $f){
            if($f == 'full_name'){
                $obj[$f] = CandidateHelper::getFullName($candidate);
            }
            else if($f == 'role'){
                $obj[$f] = ApplicationStatusHelper::getCandidateCategoryByName($candidate->role);
            }
            else if($f == 'avatar'){
                $obj[$f] = CandidateHelper::getAvatarFromCandidateId($candidate->id);
            }
             else {
                $obj[$f] = $candidate->$f;
            }
        }
        return $obj;
    }
}