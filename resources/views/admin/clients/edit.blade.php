@extends('layouts.__layout')
@section('title','Edit Client')
@section('main_content')
<div class="box">
    <div class="form-design">
        <div class="form-header">
            <div class="form-title d-flex justify-content-start align-items-center">
            <a href="{{ route('admin.clients.index') }}"><i class="fas fa-arrow-left" style="font-size: 20px;"></i></a>
                <h3 class="form-title mb-0 ml-3">Edit Client</h3>
            </div>
        </div>
        {{ Form::model($data, ['method'=>'PUT','route'=>['admin.clients.update',$data['id']], 'files'=>true ]) }}

        <div class="profile-pic-div my-4">
        <img class="img-profile rounded-circle shadow-4-strong" src="/{{ $data['avatar'] ? $data['avatar'] : 'images/user.svg' }}" style="width:100px;height:100px;object-fit:cover;" id="show">
        {{ Form::file('avatar', ['id'=>'avatar_img', 'onchange="previewImg();"']) }}
        <label for="avatar_img" id="uploadBtn">Choose Photo</label>
        </div>

            <div class='form-wrapper my-2'>
                <div class="input-group">
                    <label for="">Practice Name</label>
                    <div class="input-wrapper">
                        {{ Form::text('practice_name', null, ['class'=>'form-control', 'id'=>'clientname']) }}
                    </div>
                </div>

                <div class="input-group">
                    <label for="">Email</label>
                    <div class="input-wrapper">
                        {{ Form::email('email', null, ['class'=>'form-control', 'id'=>'email']) }}
                    </div>
                </div>

                <div class="input-group">
                    <label for="">Phone</label>
                    <div class="input-wrapper">
                        {{ Form::text('phone', null, ['class'=>'form-control', 'id'=>'phone']) }}
                    </div>
                </div>

                <div class="input-group">
                    <label for="">Address</label>
                    <div class="input-wrapper">
                        {{ Form::text('address', null, ['class'=>'form-control', 'id'=>'address']) }}
                    </div>
                </div>

                <div class="input-group">
                    <label for="">Area</label>
                    <div class="input-wrapper">
                        {{ Form::text('area', null, ['class'=>'form-control', 'id'=>'city']) }}
                    </div>
                </div>

                <div class="input-group">
                    <label for="">Post_Code</label>
                    <div class="input-wrapper">
                        {{ Form::text('post_code', null, ['class'=>'form-control', 'id'=>'city']) }}
                    </div>
                </div>

            </div>
            <button type="submit" class="form-control btn-custom-primary-blue btn-hover-outline mw-200 w-100 my-3 text-center" tabindex="1">Update</button>
            {!! Form::close() !!}
        </div>
    </div>
    @endsection