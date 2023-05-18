@extends('layouts.__layout')
@section('title','Job Description')
@section('main_content')
<div class="box" id="ShowJob">
    <div class="form-design">
        <div class="form-header">
            <div class="form-title">
                <h3 class="form-title">Job Description</h3>
            </div>
        </div>
        {{ Form::model($data, ['method'=>'PUT','route'=>['admin.clients.update',$data['id']], 'files'=>true ]) }}
        <div class="row m-4">
            <div class="col-6">
                <!-- job details -->
                <div class="form-box">
                <div class="input-group">
                    <label for="">Job Title</label>
                    <div class="input-wrapper">
                        {{ Form::text('title', null, ['class'=>'form-control', 'id'=>'jobTitle']) }}
                    </div>
                </div>
                <div class="input-group">
                    <label for="">Job Description</label>
                    <div class="input-wrapper">
                        {{ Form::textarea('description', null, ['class'=>'form-control', 'id'=>'jobDescription', 'rows'=>3, 'cols'=>3]) }}
                    </div>
                </div>
                </div>
            </div>
            <div class="col-6">
                <div class="form-box">

                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection