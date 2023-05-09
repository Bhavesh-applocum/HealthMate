<?php

namespace App\Http\Controllers;

use App\Helpers\GeneralHelper;
use App\Helpers\KeyEncryption;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class OtpController extends Controller
{
    public function __construct()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.home');
        }
    }

    public function verifyOtp(Request $request){
        $otp1     = $request->input('otpnum1', "");
        $otp2     = $request->input('otpnum2', "");
        $otp3     = $request->input('otpnum3', "");
        $otp4     = $request->input('otpnum4', "");
        $otp5     = $request->input('otpnum5', "");
        $otp6     = $request->input('otpnum6', "");

        $id = $request->input('id', "");
        $otp_code =  $otp1 . $otp2 . $otp3 . $otp4 . $otp5 . $otp6;
        $model = User::find($id);

        if($model){
            $now_stamp = date('Y-m-d H:i');
            $db_stamp = date('Y-m-d H:i', strtotime($model->otp_code_expire));

            if(strtotime($now_stamp) > strtotime($db_stamp)) {
                $message = config('params.msg_error') . ' OTP Expired Please try again! ' . config('params.msg_end');
                $request->session()->flash('message', $message);
                return redirect()->route('login')->withInput();
            }
            if($otp_code == $model->otp_code) {
                $model->otp_code = NULL;
                $length = 25;
                $randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
                $last_session_id = $randomString;
                Session::put('last_session_id', $last_session_id);
                $model->last_session_id = $last_session_id;
                $model->save();

                Auth::guard('admin')->login($model);
                $msg = Session::get('success_msg');
                Session::forget('success_msg');
                $message = config('params.msg_success') . $msg . config('params.msg_end');
                $request->session()->flash('message', $message);
                return redirect()->route('admin.dashboard');

                if($request->session()->has('redirect_to')) {
                    $redirect = $request->session()->get('redirect_to');
                    $request->session()->forget('redirect_to');
                    return Redirect::to($redirect);
                }else{
                    return redirect()->route('admin.dashboard');
                }
            }else{
                $message = config('params.msg_error') . 'Invalid OTP! Please enter correct OTP from your Email' . config('params.msg_end');
                $request->session()->flash('message', $message);
                return redirect()->route('otp_verification_form', ['id' => KeyEncryption::encrypt($model->id . '-' . '-admin')]);
            }
        }else{
            $message = config('params.msg_error') . '$type not found!' . config('params.msg_end');
            $request->session()->flash('message', $message);
            return redirect()->route('login')->withInput();
        }
    }

    public function showOtpVerificationForm($id)
    {
        $prevID = $id;
        $id = KeyEncryption::decrypt($id);
        $type = GeneralHelper::getTypeFromOTP($id);
        $id = GeneralHelper::getIdFromOTP($id);
        $model = User::find($id);
        return view('auth.otp_verification', ['model' => $model, 'type' => $type, 'resend_route_id' => $prevID]);
    }
}
