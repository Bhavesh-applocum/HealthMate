@extends('layouts.__layout')
@section('title', 'Create Contract')
@section('main_content')
<div class="box" id="ShowJob">
    <div class="form-design">
        <div class="form-header">
            <div class="form-title d-flex justify-content-start align-items-center">
                <a href="{{ route('admin.jobs.index') }}"><i class="fas fa-arrow-left" style="font-size: 20px;"></i></a>
                <h3 class="form-title mb-0 ml-3">Create Contract</h3>
            </div>
        </div>
        {{ Form::open(['method'=>'POST','route'=>['admin.jobs.store'], 'files'=>true]) }}
        <div class="row m-4">
            <div class="col-6">
                <div class="form-box">
                    <span style="font-size: 20px;font-weight:600; text-decoration:underline;">Job Details</span>
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
                    <div class="input-group my-3 d-flex flex-row justify-content-between align-items-center">
                        <label for="" class="mb-2 mr-2" style="width:135px">Category</label>
                        <div class="input-wrapper select-wrapper flex-grow-1">
                            <select name="role" class="single-select2" id="" data-placeholder="Select Category" data-search="1">
                                <option value=""></option>
                                @foreach ($data['allCategory'] as $key => $role)
                                <option value="{{ $key }}">{{ $role }}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                    <div class="input-group my-3 d-flex flex-row justify-content-between align-items-center">
                        <label for="" class="mb-0 mr-2" style="width:135px">Date</label>
                        <div class="input-wrapper flex-grow-1">
                            {{ Form::date('jobdate', null, ['class'=>'form-control', 'id'=>'jobDate']) }}
                        </div>
                    </div>
                    <div class="d-flex">
                        <!-- job start time -->
                        <div class="input-group my-3 d-flex flex-row justify-content-between align-items-center">
                            <label for="" class="mb-0 mr-2" style="width:135px">Start Time</label>
                            <div class="input-wrapper flex-grow-1">
                                {{ Form::time('job_start_time',null, ['class'=>'form-control', 'id'=>'jobTime']) }}
                            </div>
                        </div>
                        <!-- job end time -->
                        <div class="input-group my-3 d-flex flex-row justify-content-between align-items-center">
                            <label for="" class="mb-0 mr-2" style="width:135px">End Time</label>
                            <div class="input-wrapper flex-grow-1">
                                {{ Form::time('job_end_time',null, ['class'=>'form-control', 'id'=>'jobEndTime']) }}
                            </div>
                        </div>
                    </div>
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
                            {{ Form::time('break', null, ['class'=>'form-control', 'id'=>'breakTime']) }}
                        </div>
                    </div>
                    <!-- Parking -->
                    <div class="input-group my-3 d-flex flex-row justify-content-between align-items-center">
                        <label for="" class="mb-0 mr-2" style="width:135px">Parking</label>
                        <div class="input-wrapper select-wrapper flex-grow-1">
                            <select name="parking" id="" class="single-select2" data-placeholder="Select Parking Status">
                                <option value=""></option>
                                @foreach ($data['parking'] as $key => $parking )
                                <option value="{{ $key }}">{{ $parking }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="form-box">
                    <span style="font-size: 20px;font-weight:600; text-decoration:underline;">Client Details</span>
                    <div class="input-group my-3 d-flex flex-row justify-content-between align-items-center">
                        <label for="" class="mb-0 mr-2" style="width:135px">Client</label>
                        <div class="input-wrapper select-wrapper flex-grow-1">
                            <select name="client_id" id="ClientDrop" class="single-select2" data-placeholder="Select Client" data-search="1">
                                <option value=""></option>
                                @foreach ($data['AllClient'] as $key => $client )
                                <option value="{{ $client['id'] }}" data-id="{{ $client['id'] }}" data-url="{{ route('admin.jobs.clients.detail', $client['id']) }}">{{ $client['practice_name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="ClientInfo">
                        <div class="profile-pic-div m-auto my-5">
                            <img class="img-profile rounded-circle shadow-4-strong client_profile" src="" style="width:100px;height:100px;object-fit:cover;" id="show">
                            <!-- {{ Form::file('client_avatar', ['id'=>'avatar_img', 'onchange="previewImg();"']) }}
                            <label for="avatar_img" id="uploadBtn">Choose Photo</label> -->
                        </div>
                        <div class="input-group d-flex flex-row justify-content-between align-items-center my-4">
                            <label for="" class="mb-0 mr-2" style="width:135px">Email</label>
                            <div class="input-wrapper flex-grow-1">
                                {{ Form::email('client_email', null, ['class'=>'form-control clientEmail', 'id'=>'clientEmail', 'disabled']) }}
                            </div>
                        </div>
                        <div class="input-group d-flex flex-row justify-content-between align-items-center my-4">
                            <label for="" class="mb-0 mr-2" style="width:135px">Phone</label>
                            <div class="input-wrapper flex-grow-1">
                                {{ Form::text('client_phone', null, ['class'=>'form-control clientPhone', 'id'=>'clientPhone', 'disabled']) }}
                            </div>
                        </div>
                        <div class="input-group my-4 d-flex flex-row justify-content-between align-items-center">
                            <label for="" class="mb-0 mr-2 ml-0" style="width:135px">Address</label>
                            <div class="input-wrapper select-wrapper flex-grow-1">
                                <select name="address_id" id="addressForCreate" class="single-select2 clientAddress">
                                </select>
                            </div>
                        </div>
                        <div class="input-group my-4 d-flex flex-row justify-content-between align-items-center">
                            <label for="" class="mb-0 mr-2 ml-0" style="width:135px">Area</label>
                            <div class="input-wrapper flex-grow-1">
                                {{ Form::text('area', null, ['class'=>'form-control clientArea', 'id'=>'clientArea', 'disabled']) }}
                            </div>
                        </div>
                        <!-- post_code -->
                        <div class="input-group my-4 d-flex flex-row justify-content-between align-items-center">
                            <label for="" class="mb-0 mr-2 ml-0" style="width:135px">Post Code</label>
                            <div class="input-wrapper flex-grow-1">
                                {{ Form::text('post_code', null, ['class'=>'form-control postCode', 'id'=>'clientPost','disabled']) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-buttons">
            <button type="submit" class="form-control m-auto btn-custom-primary-blue btn-hover-outline mw-200 w-100">Create</button>
        </div>
        {{ Form::close() }}
    </div>
</div>
@endsection

@section('custom_scripts')
<script src="{{ asset('js/admin/job/create.js') }}"></script>
@endsection