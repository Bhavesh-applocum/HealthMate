@extends('layouts.auth.layout')
@section('title','otp verification')

@section('main_content')

<section class="main">
    <div class="auth-wrapper bg-lightest-purple">
        <div class="--header">
            <h3 class="--title font-30">OTP Verification</h3>
        </div>
        <div class="form-wrapper  mw-450">
            {!! Form::open(['route' =>'verify-otp','method'=>'POST','class'=>'','enctype'=>'multipart/form-data']) !!}
            <div class="login-title">
                <!-- @if(env('APP_ENV') == 'local' || env('APP_ENV') == 'development')
                        <p class="text-left">Please enter all Zeros (000000) as OTP in below boxes</p>
                    @else
                    @endif -->
                <p class="text-left">OTP has been sent to {{$model->email}}</p>
            </div>
            <input type="hidden" name="id" value="{{$model->id}}">
            <input type="hidden" name="type" value="{{$type}}">
            <div class="input-group">
                <div class="otp-input-wrapper">
                    <input type="text" name="otpnum1" class="otpInput" maxlength="1" autocomplete="off" autofocus>
                    <input type="text" name="otpnum2" class="otpInput" maxlength="1" autocomplete="off">
                    <input type="text" name="otpnum3" class="otpInput" maxlength="1" autocomplete="off">
                    <input type="text" name="otpnum4" class="otpInput" maxlength="1" autocomplete="off">
                    <input type="text" name="otpnum5" class="otpInput" maxlength="1" autocomplete="off">
                    <input type="text" name="otpnum6" class="otpInput" maxlength="1" autocomplete="off">
                </div>
            </div>
            <span class="additional_text">Didn't received OTP? <a href="{{ route('resend-otp',['id'=>$resend_route_id]) }}" id="resend_otp" class="text-purple redirect_link">Resend</a></span>
            <div class="input-group">
                <input type="hidden" name="recaptcha" id="recaptcha">
            </div>
            <div class="auth--input-btn">
                <button id="verify-otp" class="form-control btn-custom-primary-purple btn-hover-outline m-auto">
                    Submit OTP
                </button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</section>
@endsection

@section('custom_scripts')
<script src="{{asset('js/auth/otp_verification.js')}}"></script>
@endsection