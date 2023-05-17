<?php

namespace App\Helpers;

use App\Models\Admin;
use App\Models\Todo;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Facade\FlareClient\Stacktrace\File;
use Illuminate\Support\Facades\DB;

class GeneralHelper
{

    // public static function setActivityLog($type, $message, $user_id, $user_name)
    // {

    //     $log = new ActivityLog;
    //     $log->type = $type;
    //     $log->notes = $message;
    //     $log->user_name = $user_name;
    //     $log->created_by = $user_id;

    //     $log->created_at = date('Y-m-d H:i:s');
    //     $log->updated_at = date('Y-m-d H:i:s');
    //     $log->save();
    // }

    public static function formatDate($date)
    {
        return date('d-m-Y', strtotime($date));
    }

    public static function getFullName($candidate)
    {
        return strtolower($candidate->first_name) . ' ' . strtolower($candidate->last_name);
    }
    public static function getTypeFromOTP($id)
    {
        $type = explode('-', $id)[1];
        return $type;
    }
    public static function getIDFromOTP($id)
    {
        $id = explode('-', $id)[0];
        return $id;
    }

    public static function generateRandomString($length = 5)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }




    public static function generateCapitalRandomCode($length = 6)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    public static function getEnumValues($table, $column)
    {
        $type = DB::select(DB::raw("SHOW COLUMNS FROM $table WHERE Field = '$column'"))[0]->Type;
        preg_match('/^enum\((.*)\)$/', $type, $matches);
        $enum = array();
        foreach (explode(',', $matches[1]) as $value) {
            $v = trim($value, "'");
            $enum[$v] = $v;
        }
        return $enum;
    }


    public static function get_time_difference($time1, $time2)
    {
        return gmdate("H:i:s", strtotime($time2) - strtotime($time1));
    }



    public static function randomNumber($length)
    {
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= mt_rand(0, 9);
        }
        return $result;
    }

    public static function convertImageToBase64($path)
    {
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = File::get($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }



    public static function getDistanceBetweenPointsNew($latitude1, $longitude1, $latitude2, $longitude2)
    {
        $theta = $longitude1 - $longitude2;
        $miles = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('miles', 'feet', 'yards', 'kilometers', 'meters');
    }


    public static function words($text, $limit)
    {
        $text = strip_tags(html_entity_decode($text));
        return Str::words($text, $limit = 5, $end = ' ...');
    }


    // created by 
    public static function getAllUserRecords($id)
    {
        dd($id);
    }
    public static function getDaysDifference($end_date, $start_date, $full = false)
    {
        $now =  new DateTime($start_date);
        $ago = new DateTime($end_date);
        $diff = $now->diff($ago);
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string)  : '';
    }

    public static function adjustBrightness($hex, $steps)
    {
        // Steps should be between -255 and 255. Negative = darker, positive = lighter
        $return = "";
        if (!empty($hex) && !empty($steps)) {
            $steps = max(-255, min(255, $steps));

            // Normalize into a six character long hex string
            $hex = str_replace('#', '', $hex);
            if (strlen($hex) == 3) {
                $hex = str_repeat(substr($hex, 0, 1), 2) . str_repeat(substr($hex, 1, 1), 2) . str_repeat(substr($hex, 2, 1), 2);
            }

            // Split into three parts: R, G and B
            $color_parts = str_split($hex, 2);
            $return = '#';

            foreach ($color_parts as $color) {
                $color   = hexdec($color); // Convert to decimal
                $color   = max(0, min(255, $color + $steps)); // Adjust color
                $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
            }
        }
        return $return;
    }
    public static function generateRandomCode($length = 5)
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    //user count of today,weekly,monthly,yearly,quaterly and total
    public static function getDashboardCounts($type, $time)
    {
        $model = '';
        $count = 0;
        if ($type == 0) {
            $model = 'App\Client';
        } else if ($type == 1 ){
            $model = 'App\Candidate';
        }else if ($type == 2) {
            $model = 'App\Application';
        }else if($type == 3) {
            $model = 'App\Invoice';
        }else if($type == 4) {
            $model = 'App\Timesheet';
        }
        if ($time == 0) {
            $count = $model::whereDate('created_at', Carbon::today())->count();
        } elseif ($time == 1) {
            $count = $model::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        } elseif ($time == 2) {
            $count = $model::whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->count();
        } elseif ($time == 3) {
            $count = $model::whereBetween('created_at', [Carbon::now()->startOfQuarter(), Carbon::now()->endOfQuarter()])->count();
        } elseif ($time == 4) {
            $count = $model::whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])->count();
        } elseif ($time == 5) {
            $count = $model::all()->count();
        }
        return $count;
    }
    public static function generateRandomSessionId()
    {
        $length = 25;
        $randomString     = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
        return $randomString;
    }

    public static function GenderFullName($gender)
    {
        if ($gender == 'M') {
            return 'Male';
        }
        elseif ($gender == 'F') {
            return 'Female';
        }
        else{
            return 'Other';
        }
    }
}
