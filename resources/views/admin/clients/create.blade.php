@extends('layouts.__layout')
@section('title','Create Client')
@section('main_content')

<div class="box">
    <div class="form-design">
        <div class="form-header">
            <div class="form-title">
                <h3 class="form-title">Create Client</h3>
            </div>
        </div>
        {!! Form::open(['method'=>'POST','route'=>'admin.clients.store','id'=>'create_client','class'=>'create_client', 'files'=>true ]) !!}
        <div class="profile-pic-div m-auto my-4">
            <img class="img-profile rounded-circle  shadow-4-strong" src="/images/user.svg" style="width:100px;height:100px;object-fit:cover;" id="show">
            {{ Form::file('avatar', ['id'=>'avatar_img', 'onchange="previewImg();"']) }}
            <label for="avatar_img" id="uploadBtn">Choose Photo</label>
        </div>

        <div class="form-wrapper my-2">
            <div class="input-group">
                <label for="">Practice Name</label>
                <div class="input_wrapper">
                <input type="text" name="practice_name" class="form-control" placeholder="Practice Name" value="{{ old('practice_name') }}">
                </div>
            </div>

            <div class="input-group">
                <label for="">Email</label>
                <div class="input_wrapper">
                <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
                </div>
            </div>

            <div class="input-group">
                <label for="">Phone</label>
                <div class="input_wrapper">
                <input type="text" name="phone" class="form-control" placeholder="Phone Number" value="{{ old('phone') }}">
                </div>
            </div>

            <div class="input-group">
                <label for="">Address</label>
                <div class="input_wrapper">
                <input type="text" name="address" class="form-control" placeholder="Address" value="{{ old('address') }}">
                </div>
            </div>

            <div class="input-group">
                <label for="">Area</label>
                <div class="input_wrapper">
                <input type="Area" name="area" class="form-control" placeholder="Area" value="{{ old('area') }}">
                </div>
            </div>

            <div class="input-group">
                <label for="">Post_Code</label>
                <div class="input_wrapper">
                <input type="text" name="post_code" class="form-control" placeholder="Post Code" value="{{ old('post_code') }}">
                </div>
            </div>

            <div class="input-group">
                <label>Password</label>
                <div class="input_wrapper password_wrapper">
                    <label for="showPassword" class="showpassword_wrapper">
                        <input type="checkbox" name="showPassword" id="showPassword" class="showPassword">
                        <img src="{{ asset('images/eye.svg') }}" alt="">
                    </label>
                    <input name="password" id="password"class="password" type="password" placeholder="Password" value="Password1!">
                </div>
            </div>

            <div class="input-group">
                <label>Confirm Password</label>
                <div class="input_wrapper password_wrapper">
                    <label for="showPasword" class="showpassword_wrapper">
                        <input type="checkbox" name="showPassword" id="showPasword" class="showPassword">
                    </label>
                    <input name="confirmPassword" class="password" type="password"placeholder="Confirm-Password" value="Password1!">
                </div>
            </div>
        </div>
        <div class="form-buttons">
            <button type="submit" class="form-control m-auto btn-custom-primary-blue btn-hover-outline mw-200 w-100">Create</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection

@section('custom_scripts')
<script src="{{asset('js/common/clients/create.js')}}"></script>
@endsection