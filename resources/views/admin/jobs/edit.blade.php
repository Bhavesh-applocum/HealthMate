@extends('layouts.__layout')
@section('title','Edit Contract')
@section('main_content')
<span id="get_url" "></span>
<div class="box" id="ShowJob">
    <div class="form-design">
        <div class="form-header">
            <div class="form-title d-flex justify-content-start align-items-center">
            <a href="{{ URL::previous() }}"><i class="fas fa-arrow-left" style="font-size: 20px;"></i></a>
                <h3 class="form-title mb-0 ml-3">Edit Contract</h3>
            </div>
        </div>
        {{ Form::model($data, ['method'=>'PUT','route'=>['admin.clients.update',$data['id']], 'files'=>true ]) }}
        <div class="row m-4">
            <div class="col-6">
                <!-- job details -->
                <div class="form-box">
                    <span style="font-size: 20px;font-weight:600; text-decoration:underline;">Job Details</span>
                    <!-- job status in span -->
                    <span class="float-right py-1 px-3 job-status" data-status="{{ $data['status'] }}" style="font-size: 16px; font-weight:400; color:white;">{{ $data['status'] }}</span>
                    <div class="input-group my-3 d-flex flex-row justify-content-between align-items-center">
                        <label for="" class="mb-0 mr-2" style="width:135px">Title</label>
                        <div class="input-wrapper flex-grow-1">
                            {{ Form::text('title', null, ['class'=>'form-control', 'id'=>'jobTitle']) }}
                        </div>
                    </div>
                    <div class="input-group my-3 d-flex flex-row justify-content-between align-items-center">
                        <label for="" class="mb-2 mr-2" style="width:135px">Description</label>
                        <div class="input-wrapper flex-grow-1">
                            {{ Form::textarea('description', null, ['class'=>'form-control', 'id'=>'jobDescription', 'rows'=>3, 'cols'=>3]) }}
                        </div>
                    </div>
                    <!-- job category  -->
                    <div class="input-group my-3 d-flex flex-row justify-content-between align-items-center">
                    <label for="" class="mb-2 mr-2" style="width:135px">Category</label>
                    <div class="input-wrapper select-wrapper flex-grow-1">
                        <select name="role" class="single-select2" id="">
                            @foreach ($data['AllCategories'] as $key => $role)
                            @php
                                $isSelected = $role == $data['job_category'];
                            @endphp
                            <option value="{{ $key }}" {{ $isSelected ? "selected" : "" }}>{{ $role }}</option>
                            @endforeach
                            
                        </select>
                    </div>
                </div>
                    <!-- job date -->
                    <div class="input-group my-3 d-flex flex-row justify-content-between align-items-center">
                        <label for="" class="mb-0 mr-2" style="width:135px">Date</label>
                        <div class="input-wrapper flex-grow-1">
                            {{ Form::date('jobdate', \Carbon\Carbon::parse($data['job_date']), ['class'=>'form-control', 'id'=>'jobDate']) }}
                        </div>
                    </div>
                    <!-- job time -->
                    <div class="input-group my-3 d-flex flex-row justify-content-between align-items-center">
                        <label for="" class="mb-0 mr-2" style="width:135px">Start Time</label>
                        <div class="input-wrapper flex-grow-1">
                            {{ Form::time('job_start_time', \Carbon\Carbon::parse($data['job_start_time']), ['class'=>'form-control', 'id'=>'jobTime']) }}
                        </div>
                    </div>
                    <!-- job end time -->
                    <div class="input-group my-3 d-flex flex-row justify-content-between align-items-center">
                        <label for="" class="mb-0 mr-2" style="width:135px">End Time</label>
                        <div class="input-wrapper flex-grow-1">
                            {{ Form::time('job_end_time', \Carbon\Carbon::parse($data['job_end_time']), ['class'=>'form-control', 'id'=>'jobEndTime']) }}
                        </div>
                    </div>
                    <!-- job salary -->
                    <div class="input-group my-3 d-flex flex-row justify-content-between align-items-center">
                        <label for="" class="mb-0 mr-2" style="width:135px">Salary</label>
                        ₹ &nbsp;&nbsp; 
                        <div class="input-wrapper flex-grow-1">
                            {{ Form::text('salary', null, ['class'=>'form-control', 'id'=>'jobSalary']) }}
                        </div>
                    </div>
                    <!-- break time -->
                    <div class="input-group my-3 d-flex flex-row justify-content-between align-items-center">
                        <label for="" class="mb-0 mr-2" style="width:135px">Break Time</label>
                        <div class="input-wrapper flex-grow-1">
                            {{ Form::time('break', \Carbon\Carbon::parse($data['break']), ['class'=>'form-control', 'id'=>'breakTime']) }}
                        </div>
                        &nbsp;&nbsp;/ Min
                    </div>
                    <!-- Parking -->
                    <div class="input-group my-3 d-flex flex-row justify-content-between align-items-center">
                        <label for="" class="mb-0 mr-2" style="width:135px">Parking</label>
                        <div class="input-wrapper select-wrapper flex-grow-1">
                            <select name="parking" id="" class="single-select2">
                                @foreach ($data['AllParking'] as $key => $parking )
                                    @php
                                        $isSelected = $parking == $data['parking'];
                                    @endphp
                                    <option value="{{ $key }}" {{ $isSelected ? "selected" : "" }}>{{ $parking }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- Job Created At -->
                    <div class="input-group my-3 d-flex flex-row justify-content-between align-items-center">
                        <label for="" class="mb-0 mr-2" style="width:135px">Created At</label>
                        <div class="input-wrapper flex-grow-1">
                            {{ Form::text('created_at', null, ['class'=>'form-control', 'id'=>'createdAt', 'readonly']) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="form-box">
                    <span class="d-block mb-3" style="font-size: 20px;font-weight:600; text-decoration:underline;">Client Details</span>
                    <div class="profile-pic-div m-auto my-5">
                        <img class="img-profile rounded-circle shadow-4-strong" src="/{{ $data['client_avatar'] ? $data['client_avatar'] : 'images/user.svg' }}" style="width:100px;height:100px;object-fit:cover;" id="show">
                        <!-- {{ Form::file('client_avatar', ['id'=>'avatar_img', 'onchange="previewImg();"']) }}
                        <label for="avatar_img" id="uploadBtn">Choose Photo</label> -->
                    </div>
                    <div class="input-group d-flex flex-row justify-content-between align-items-center  my-4">
                        <label for="" class="mb-0 mr-2" style="width:135px">Name</label>
                        <div class="input-wrapper flex-grow-1">
                            {{ Form::text('client', null, ['class'=>'form-control', 'id'=>'clientName', 'readonly']) }}
                        </div>
                    </div>
                    <div class="input-group d-flex flex-row justify-content-between align-items-center my-4">
                        <label for="" class="mb-0 mr-2" style="width:135px">Email</label>
                        <div class="input-wrapper flex-grow-1">
                            {{ Form::email('client_email', null, ['class'=>'form-control', 'id'=>'clientEmail', 'readonly']) }}
                        </div>
                    </div>
                    <div class="input-group d-flex flex-row justify-content-between align-items-center my-4">
                        <label for="" class="mb-0 mr-2" style="width:135px">Phone</label>
                        <div class="input-wrapper flex-grow-1">
                            {{ Form::text('client_phone', null, ['class'=>'form-control', 'id'=>'clientPhone', 'readonly']) }}
                        </div>
                    </div>
                    <div class="input-group my-4 d-flex flex-row justify-content-between align-items-center">
                        <label for="" class="mb-0 mr-2 ml-0" style="width:135px">Address</label>
                        <div class="input-wrapper select-wrapper flex-grow-1">
                            <select name="address" id="addressDrop" class="single-select2">
                                @foreach ($data['AllClientAddress'] as $key => $address )
                                    @php
                                        $isSelected = $address['address'] == $data['address'];
                                    @endphp
                                    <option data-id="{{ $address['id'] }}" data-url="{{ route('admin.jobs.area.edit', $address['id']) }}" value="{{ $address['id'] }}" {{ $isSelected ? "selected" : "" }}>{{ $address['address'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- area  -->
                    <div class="input-group my-4 d-flex flex-row justify-content-between align-items-center">
                        <label for="" class="mb-0 mr-2 ml-0" style="width:135px">Area</label>
                        <div class="input-wrapper flex-grow-1">
                            {{ Form::text('area', null, ['class'=>'form-control area-edit', 'id'=>'clientArea']) }}
                        </div>
                    </div>
                    <!-- post_code -->
                    <div class="input-group my-4 d-flex flex-row justify-content-between align-items-center">
                        <label for="" class="mb-0 mr-2 ml-0" style="width:135px">Post Code</label>
                        <div class="input-wrapper flex-grow-1">
                            {{ Form::text('post_code', null, ['class'=>'form-control pc-edit', 'id'=>'clientPost']) }}
                        </div>
                    </div>  
                </div>
            </div>
        </div>
        <a href="#" class="form-control btn-custom-primary-blue btn-hover-outline m-auto mw-200 w-100 my-3 text-center edit-contract" tabindex="1">Edit</a>
        {!! Form::close() !!}
    </div>
</div>
@endsection