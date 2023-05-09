<?php

namespace App\Http\Controllers;

use App\Helpers\GeneralHelper;
use App\Helpers\KeyEncryption;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function __construct()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $model = User::where('username', $request->input('username'))->first();

        if ($model) {
            if (Hash::check($request->input('password'), $model->password)) {
                $randomCode = GeneralHelper::generateRandomCode(6);
                $mail = $model->email;

                $model->otp_code = 000000;
                $model->otp_code_expire = date('Y-m-d H:i:s', strtotime('now +5 minutes'));
                $model->save();

                $subject = 'OTP for Login';
                if (isset($mail) && $mail != null && $mail != '') {
                    $data = array(
                        'name' => $model->name,
                        'email' => $mail,
                        'otp_code' => $randomCode,
                    );
                    try {
                        // Mail::send('emails.otp_verification', $data, function ($message) use ($mail, $subject) {
                        //   $message->to($mail)->subject($subject);
                        //   $message->from(config('params.email_from'), config('params.email_from_name'));
                        // });
                        $message = config('params.msg_success') . 'Please check your email for OTP. ' . config('params.msg_end');
                        $request->session()->flash('message', $message);
                        Session::put('success_msg', 'Login successful!');
                        return redirect()->route('otp_verification_form', ['id' => KeyEncryption::encrypt($model->id . '-admin')]);
                    } catch (\Exception $e) {
                        $message = config('params.msg_error') . 'Something went wrong!' . config('params.msg_end');
                        $request->session()->flash('message', $message);
                        return redirect()->route('login')->withInput();
                    }
                }
            } else {
                $message = config('params.msg_error') . ' Invalid email or password!' . config('params.msg_end');
                $request->session()->flash('message', $message);
                return redirect()->route('login')->withInput();
            }
        } else {
            $message = config('params.msg_error') . 'Email not found!' . config('params.msg_end');
            $request->session()->flash('message', $message);
            return redirect()->route('login')->withInput();
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $message = config('params.msg_error') . 'Logged out' . config('params.msg_end');
        $request->session()->flash('message', $message);
        return redirect()->route('login');
    }
}
