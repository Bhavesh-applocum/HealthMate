@extends('layouts.__layout')
@section('title','Edit Candidate')
@section('main_content')

<div class="box">
    <div class="form-design">
        <div class="form-header">
                <div class="form-title d-flex justify-content-start align-items-center">
                <a href="{{ route('admin.candidates.index') }}"><i class="fas fa-arrow-left" style="font-size: 20px;"></i></a>
                <h3 class="form-title mb-0 ml-3">Edit Candidate</h3>
            </div>
        </div>
        {{ Form::model($data, ['method'=>'PUT','route'=>['admin.candidates.update',$data['id']], 'files'=>true ]) }}

        <div class="profile-pic-div my-4">
        <img class="img-profile rounded-circle shadow-4-strong" src="/{{ $data['avatar'] ? $data['avatar'] : 'images/user.svg' }}" style="width:100px;height:100px;object-fit:cover;" id="show">
        {{ Form::file('avatar', ['id'=>'avatar_img', 'onchange="previewImg();"']) }}
        <label for="avatar_img" id="uploadBtn">Choose Photo</label>
        </div>

            <div class='form-wrapper my-2'>
                <div class="input-group">
                    <label for="">First Name</label>
                    <div class="input-wrapper">
                        {{ Form::text('first_name', null, ['class'=>'form-control', 'id'=>'firstname']) }}
                    </div>
                </div>

                <div class="input-group">
                    <label for="">Last Name</label>
                    <div class="input-wrapper">
                        {{ Form::text('last_name', null, ['class'=>'form-control', 'id'=>'lastname']) }}
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
                    <label for="">Gender</label>
                    <div class="input_wrapper select-wrapper">
                        <select name="gender" class="single-select2" id="">
                            @php
                                $genders = ['M'=>'Male', 'F'=>'Female', 'O'=>'Other'];
                            @endphp
                            @foreach ($genders as $key => $gender)
                                @php
                                    $isSelected = $gender == $data['gender'];
                                @endphp
                                <option value="{{ $key }}" {{ $isSelected ? "selected" : "" }}>{{ $gender }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="input-group">
                    <label for="">Role</label>
                    <div class="input-wrapper select-wrapper">
                        <select name="role" class="single-select2" id="">
                            @foreach ($data['AllRole'] as $key => $role)
                            @php
                                $isSelected = $role == $data['role'];
                            @endphp
                            <option value="{{ $key }}" {{ $isSelected ? "selected" : "" }}>{{ $role }}</option>
                            @endforeach
                            
                        </select>
                    </div>
                </div>
            </div>
            <button type="submit" class="form-control btn-custom-primary-blue btn-hover-outline mw-200 w-100 my-3 text-center" tabindex="1">Update</button>
            {!! Form::close() !!}
        </div>
    </div>
    @endsection