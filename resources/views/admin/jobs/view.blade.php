@extends('layouts.__layout')
@section('title','Job Description')
@section('main_content')
<div class="box" id="ShowJob">
    <div class="form-design">
        <div class="form-header">
            <div class="form-title d-flex justify-content-start align-items-center">
            <a href="{{ route('admin.jobs.index') }}"><i class="fas fa-arrow-left" style="font-size: 20px;"></i></a>
                <h3 class="form-title mb-0 ml-3">Job Description</h3>
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
                            {{ Form::text('title', null, ['class'=>'form-control', 'id'=>'jobTitle', 'readonly']) }}
                        </div>
                    </div>
                    <div class="input-group my-3 d-flex flex-row justify-content-between align-items-center">
                        <label for="" class="mb-2 mr-2" style="width:135px">Description</label>
                        <div class="input-wrapper flex-grow-1">
                            {{ Form::textarea('description', null, ['class'=>'form-control', 'id'=>'jobDescription', 'rows'=>3, 'cols'=>3, 'readonly']) }}
                        </div>
                    </div>
                    <!-- job category  -->
                    <div class="input-group my-3 d-flex flex-row justify-content-between align-items-center">
                        <label for="" class="mb-0 mr-2" style="width:135px">Category</label>
                        <div class="input-wrapper flex-grow-1">
                            {{ Form::text('job_category', null, ['class'=>'form-control', 'id'=>'jobCategory', 'readonly']) }}
                        </div>
                    </div>
                    <!-- job date -->
                    <div class="input-group my-3 d-flex flex-row justify-content-between align-items-center">
                        <label for="" class="mb-0 mr-2" style="width:135px">Date</label>
                        <div class="input-wrapper flex-grow-1">
                            {{ Form::text('job_date', null, ['class'=>'form-control', 'id'=>'jobDate', 'readonly']) }}
                        </div>
                    </div>
                    <!-- job time -->
                    <div class="input-group my-3 d-flex flex-row justify-content-between align-items-center">
                        <label for="" class="mb-0 mr-2" style="width:135px">Time</label>
                        <div class="input-wrapper flex-grow-1">
                            {{ Form::text('job_time', null, ['class'=>'form-control', 'id'=>'jobTime', 'readonly']) }}
                        </div>
                    </div>
                    <!-- job salary -->
                    <div class="input-group my-3 d-flex flex-row justify-content-between align-items-center">
                        <label for="" class="mb-0 mr-2" style="width:135px">Salary</label>
                        ₹ &nbsp;&nbsp; 
                        <div class="input-wrapper flex-grow-1">
                            {{ Form::text('salary', null, ['class'=>'form-control', 'id'=>'jobSalary', 'readonly']) }}
                        </div>
                    </div>
                    <!-- break time -->
                    <div class="input-group my-3 d-flex flex-row justify-content-between align-items-center">
                        <label for="" class="mb-0 mr-2" style="width:135px">Break Time</label>
                        <div class="input-wrapper flex-grow-1">
                            {{ Form::text('break', null, ['class'=>'form-control', 'id'=>'breakTime', 'readonly']) }}
                        </div>
                        &nbsp;&nbsp;/ Min
                    </div>
                    <!-- Parking -->
                    <div class="input-group my-3 d-flex flex-row justify-content-between align-items-center">
                        <label for="" class="mb-0 mr-2" style="width:135px">Parking</label>
                        <div class="input-wrapper flex-grow-1">
                            {{ Form::text('parking', null, ['class'=>'form-control', 'id'=>'parking', 'readonly']) }}
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
                        <div class="input-wrapper flex-grow-1">
                            {{ Form::textarea('address', null, ['class'=>'form-control', 'id'=>'clientPhone','rows'=>3, 'cols'=>3, 'readonly']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- if job status is booking or worked then hide edit button -->
        @if($data['status'] == 'Published' || $data['status'] == 'Application')
        <a href="{{ route('admin.jobs.edit',$data['id']) }}" class="form-control btn-custom-primary-blue btn-hover-outline m-auto mw-200 w-100 my-3 text-center editJobBtn" tabindex="1">Edit</a>
        @endif
        {!! Form::close() !!}
    </div>
</div>
@endsection

@section('custom_scripts')
<script src="{{ asset('js/admin/job/view.js') }}"></script>
@endsection