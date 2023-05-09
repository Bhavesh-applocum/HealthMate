@extends('layouts.auth.layout')
@section('title','Login')
@section('main_content')

<section class="main">
  <div class="auth-wrapper bg-lightest-green">
    <div class="--header">
      <h3 class="--title font-30">Log in</h3>
    </div>
    <div class="form-wrapper mw-400">
      {!! Form::open(['route' =>'login','method'=>'POST','class'=>'login_form','id'=>'login_form','enctype'=>'multipart/form-data']) !!}
      <div class="form-common-alert"></div>
      <div class="input-group">
        <label>User Name</label>
        <div class="input_wrapper">
          <input name="username" type="text" class="form-control" placeholder="User Name" value="{{old('username')}}">
        </div>
      </div>
      <div class="input-group">
        <label>Password</label>
        <div class="input_wrapper password_wrapper">
          <label for="showPassword" class="showpassword_wrapper">
            <input type="checkbox" name="showPassword" id="showPassword" class="showPassword">
            <img src="{{ asset('images/eye.svg') }}" alt="">
          </label>
          <input name="password" type="password" class="form-control" placeholder="Password" value="{{old('password')}}">
        </div>
      </div>
      <div class="auth--input-btn">
        <button class="form-control btn-custom-primary btn-hover-outline">
          Sign in
        </button>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</section>
@endsection


@section('custom_scripts')
<script src="{{asset('js/auth/login.js')}}"></script>
<script src="{{asset('js/auth/common.js')}}"></script>
@endsection