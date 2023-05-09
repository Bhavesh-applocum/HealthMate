<?php

namespace App\Http\Controllers\Api;

use App\Candidate;
use App\Client;
use App\Helpers\ApplicationStatusHelper;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Mail\ForgotPasswordMail;
use App\Mail\LoginAuthMail;
use App\Http\Controllers\Controller;
use App\PasswordReset;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use PhpParser\Node\Expr\Cast\Object_;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\URL;
use Psy\TabCompletion\Matcher\FunctionsMatcher;

class LoginController extends Controller
{

    public function login(LoginRequest $request)
    {
        $candidate = Candidate::where(['email' => $request->email])->first();
        $client = Client::where('email', $request->email)->first();
        if ($candidate) {
            if (Hash::check($request->password, $candidate->password)) {
                $candidate->roleName = ApplicationStatusHelper::getCandidateCategoryByName($candidate->role);
                return response()->json([
                    'message' => 'Login Successful',
                    'type' => 2,
                    'code' => 200,
                    'data' => [$candidate]
                ], 200);
        
            } else {
                return response()->json([
                    'message' => 'password is incorrect',
                    'status' => 'Bad Request',
                    'code' => 401
                ], 401);
            }
        } 
        else if ($client) {
            if (Hash::check($request->password, $client->password)) {
                
                return response()->json([
                    'message' => 'Login Successful',
                    'type' => 1,
                    'code' => 200,
                    'data' => [$client],
                ], 200);
            } else {
                return response()->json([
                    'message' => 'password is incorrect',
                    'status' => 'Bad Request',
                    'code' => 400
                ], 400);
            }
        } 
        else {
            return response()->json([
                'message' => 'User not found',
                'status' => 'Bad Request',
                'code' => 400
            ], 400);
        }
    }

    public function verifyotp(Request $request)
    {
        $otp1 = $request->otp1;
        $otp2 = $request->otp2;
        $otp3 = $request->otp3;
        $otp4 = $request->otp4;

        $otp = $otp1 . $otp2 . $otp3 . $otp4;

        $userType = $request->type;
        $id = $request->id;

        $model = '';
        if ($userType == 1) {
            $model = Client::find($id);
        } else {
            $model = Candidate::find($id);
        }

        if ($model->Login_otp_expire < now()) {
            return response()->json([
                'message' => 'OTP Expired',
                'status' => 'Bad Request',
                'code' => 400
            ], 400);
        } else {
            if ($model->Login_otp == $otp) {
                $model->save();
                return response()->json([
                    'message' => 'OTP Verified',
                    'status' => 'success',
                    'code' => 200
                ], 200);
            } else {
                return response()->json([
                    'message' => 'OTP is incorrect',
                    'status' => 'Bad Request',
                    'code' => 400
                ], 400);
            }
        }
    }

    public function resendotp(Request $request)
    {
        $userType = $request->type;
        $id = $request->id;


        $model = '';
        if ($userType == 1) {
            $model = Client::find($id);
        } else {
            $model = Candidate::find($id);
        }

        $otp = rand(1000,9999);
        $otp_expire = now()->addMinutes(5);
        $model->Login_otp = $otp;
        $model->Login_otp_expire = $otp_expire;
        $model->save();

        Mail::to($model->email)->send(new LoginAuthMail($otp));

        return response()->json([
            'message' => 'OTP Resend',
            'status' => 'success',
            'code' => 200
        ], 200);
    }

    public function forgotpassword(Request $request){

        $qry            = Candidate::where('email', $request->email);
        $candidate      = $qry->first();
        $candidate_cnt  = $qry->count();
        $qry1           = Client::where('email', $request->email);
        $client         = $qry1->first();
        $client_cnt     = $qry1->count();

        
        if($client_cnt > 0){
            $otp = rand(1000,9999);
            $otp_expire = now()->addMinutes(5);
            $upadte = Client::where('id', $client->id)
            ->update([
                'otp_expire'=>now()->addMinutes(5),
                'forgot_password_otp'=>$otp,
                'forgot_password_otp_expire'=>$otp_expire
            ]);
                Mail::to($client->email)->send(new ForgotPasswordMail($otp));

                return response()->json([
                    'message' => 'Otp sent',
                    'type' => 1,
                    'status' => 'Success',
                    'code' => 200,
                    'data' => [$client]
                ], 200);
            }
            else if($candidate_cnt > 0){
                $otp = rand(1000,9999);
                $otp_expire = now()->addMinutes(5);
                $upadte = Candidate::where('id', $candidate->id)
                ->update([
                    'forgot_password_otp_expire'=>now()->addMinutes(5),
                    'forgot_password_otp'=>$otp,
                    'forgot_password_otp_expire'=>$otp_expire
                ]);
                Mail::to($candidate->email)->send(new ForgotPasswordMail($otp));

                return response()->json([
                    'message' => 'Otp sent',
                    'type' => 2,
                    'status' => 'Success',
                    'code' => 200,
                    'data' => [$candidate]
                ], 200);
            }
            else{
                return response()->json([
                    'message' => 'User not found',
                    'status' => 'Bad Request',
                    'code' => 400
                ], 400);
            }
        }

    public function verifyforgototp(Request $request){

        $otp1 = $request->otp1;
        $otp2 = $request->otp2;
        $otp3 = $request->otp3;
        $otp4 = $request->otp4;

        $otp = $otp1 . $otp2 . $otp3 . $otp4;

        $userType = $request->type;
        $id = $request->id;

        $model = '';
        if ($userType == 1) {
            $model = Client::find($id);
        } else {
            $model = Candidate::find($id);
        }

        if ($model->forgot_password_otp_expire < now()) {
            return response()->json([
                'message' => 'OTP Expired',
                'status' => 'Bad Request',
                'code' => 400
            ], 400);
        } else {
            if ($model->forgot_password_otp == $otp) {
                $model->save();
                return response()->json([
                    'message' => 'OTP Verified',
                    'status' => 'success',
                    'code' => 200
                ], 200);
            } else {
                return response()->json([
                    'message' => 'OTP is incorrect',
                    'status' => 'Bad Request',
                    'code' => 400
                ], 400);
            }
        }
    }

    public function resendforgotpasswordotp(Request $request){
        $userType = $request->type;
        $id = $request->id;


        $model = '';
        if ($userType == 1) {
            $model = Client::find($id);
        } else {
            $model = Candidate::find($id);
        }

        $otp = rand(1000,9999);
        $otp_expire = now()->addMinutes(5);
        $model->forgot_password_otp = $otp;
        $model->forgot_password_otp_expire = $otp_expire;
        $model->save();

        Mail::to($model->email)->send(new ForgotPasswordMail($otp));

        return response()->json([
            'message' => 'OTP Resend',
            'status' => 'success',
            'code' => 200
        ], 200);
    }

    public function resetpassword(ResetPasswordRequest $request){

        $userType = $request->type;
        $id = $request->id;

        $model = '';

        if($userType == 1){
            $model = Client::find($id);
        }
        else{
            $model = Candidate::find($id);
        }

        $model->password = Hash::make($request->password);
        $model->save();

        return response()->json([
            'message' => 'Password Reset Successfully',
            'status' => 'success',
            'code' => 200
        ], 200);
    }
}


/*
|--------------------------------------------------------------------------
| Send mail for reset password link and change password using web view
|--------------------------------------------------------------------------
|
| 
|

    // public function forgotpassword(Candidate $candidate, Client $client, Request $request)
    // {

    //     try {
    //         $candidate = Candidate::where('email', $request->email)->get();
    //         $client = Client::where('email', $request->email)->get();

    //         if (count($candidate) > 0) {

    //             $token = Str::random(40);
    //             $domain = URL::to('/');
    //             $url =  $domain . '/resetpassword?token=' . $token . '&type=2&email=' . $candidate[0]->email;

    //             $data['url'] = $url;
    //             $data['email'] = $request->email;
    //             $data['title'] = "Reset Password";
    //             $data['body'] = "Please click on the link to reset your password.";

    //             Mail::send('forgetPasswordMail', ['data' => $data], function ($message) use ($data) {
    //                 $message->to($data['email'])->subject($data['title']);
    //             });

    //             $datetime = Carbon::now()->format('Y-m-d H:i:s');
    //             PasswordReset::updateOrCreate(
    //                 ['email' => $request->email],
    //                 [
    //                     'email' => $request->email,
    //                     'token' => $token,
    //                     'created_at' => $datetime
    //                 ]
    //             );

    //             return response()->json([
    //                 'message' => 'Reset Password Link Sent to your Email',
    //                 'status' => 'success',
    //                 'code' => 200
    //             ], 200);
    //         } else if (count($client) > 0) {
    //             $token = Str::random(40);
    //             $domain = URL::to('/');
    //             $url = $domain . '/resetpassword?token=' . $token . '&type=1&email=' . $client[0]->email;

    //             $data['url'] = $url;
    //             $data['email'] = $request->email;
    //             $data['title'] = "Reset Password";
    //             $data['body'] = "Please click on the link to reset your password.";

    //             Mail::send('forgetPasswordMail', ['data' => $data], function ($message) use ($data) {
    //                 $message->to($data['email'])->subject($data['title']);
    //             });

    //             $datetime = Carbon::now()->format('Y-m-d H:i:s');
    //             PasswordReset::updateOrCreate(
    //                 ['email' => $request->email],
    //                 [
    //                     'email' => $request->email,
    //                     'token' => $token,
    //                     'created_at' => $datetime
    //                 ]
    //             );

    //             return response()->json([
    //                 'message' => 'Reset Password Link Sent to your Email',
    //                 'status' => 'success',
    //                 'code' => 200
    //             ], 200);
    //         } else {
    //             return response()->json([
    //                 'message' => 'User not found',
    //                 'status' => 'Bad Request',
    //                 'code' => 400
    //             ], 400);
    //         }
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'User not found',
    //             'status' => 'Bad Request',
    //             'code' => 400
    //         ], 400);
    //     }
    // }

    // //reset password view page load
    // public function forgotpasswordLoad(Request $request)
    // {
    //     $resetData = PasswordReset::where('token', $request->token)->count();
    //     $type = $request->type;
    //     $email = $request->email;

    //     $model = '';
    //     if ($resetData > 0) {
    //         if ($type == 1) {
    //             $model = Client::where('email', $email)->first();
    //         } else {
    //             $model = Candidate::where('email', $email)->first();
    //         }
    //         return view('resetPassword', ['model' => $model, 'type' => $type]);
    //     } else {
    //         return view('404');
    //     }
    // }

    // //reset password functionality

    // public function resetpassword(Request $request)
    // {
    //     $request->validate([
    //         'password' => [
    //             'required',
    //             'string',
    //             'min:8',             // must be at least 10 characters in length
    //             'regex:/[a-z]/',      // must contain at least one lowercase letter
    //             'regex:/[A-Z]/',      // must contain at least one uppercase letter
    //             'regex:/[0-9]/',      // must contain at least one digit
    //             'regex:/[@$!%*#?&]/', // must contain a special character
    //         ],
    //         'confirmPassword' => 'required|same:password',
    //     ]);
    //     $userType = $request->type;
    //     $email = $request->email;
    //     if ($userType == 1) {
    //         $model = Client::where('email', $email)->first();
    //     } else {
    //         $model = Candidate::where('email', $email)->first();
    //     }
    //     $model->password = Hash::make($request->password);
    //     $model->save();

        
    //     $email = $model->email;
    //     PasswordReset::where('email', $email)->delete();
        
    //     return view('success');

    //     return response()->json([
    //         'message' => 'Password Reset Successfully',
    //         'status' => 'success',
    //         'code' => 200
    //     ], 200);
    // }
*/

