@extends('layouts.__layout')
@section('title','contracts')
@section('main_content')
<span id="fetch_data" data-url="{{ route('admin.jobs.index') }}"></span>
<div class="box">
    <div class="dataTable-design">
        <div class="dataTable-title-wrapper">
            <div class="inner">
                <div class="left">
                    <h3 class="dataTable-title">Contracts</h3>
                </div>
                <div class="right">
                    <div class="dataTable-search-wrapper">
                        <input type="text" placeholder="search table" id="dataTable-search">
                        <button class="dataTable-search-clear">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <a href="#" class="icon-btn icon-btn-lg ml-2 __shadow icon-btn-primary-purple icon-btn-hover-outline">
                        <i class="fa-solid fa-plus"></i>
                    </a>
                </div>
            </div>
        </div>
        @include('partials.job.__table')
    </div>
</div>
@endsection

@section('custom_scripts')
<script src="{{asset('js/admin/job/index.js')}}"></script>
@endsection