@extends('layouts.__layout')
@section('title','dashboard')
@section('style')
<link rel="stylesheet" href="{{ asset('css/pages/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/pages/timesheetChart.css') }}">
<!-- <link rel="stylesheet" href="{{ asset('css/pages/categoryTypeList.css') }}"> -->
@endsection
@section('main_content')
<span id="getDashboardInfo" data-url="{{ route('admin.getDashboardInfo') }}"></span>
<span id="dashboardTimesheetInfo" data-url="{{ route('admin.getTimesheetChartInfo') }}"></span>
<span id="dashboardCategoryInfo" data-url="{{ route('admin.getCategoryInfo') }}"></span>
<div class="box">
    <div class="count_info_wrapper">
        <div class="count_info_single client_count_info">
            <div class="inner">
                <div class="count_info_header">
                    <div class="title">Client Created</div>
                    <div class="dropdown">
                        <button class="icon-btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="{{ asset('images/ellipsis-menu.svg') }}" alt="menu">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                            @foreach($CountDurationForDashboard as $key => $value)
                            <a class="dropdown-item get_dashboard_count {{ $key === 0 ? 'active' : '' }}" href="javascript:;" data-type="0" data-time="{{ $value['time'] }}">{{ $value['title'] }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="count_info_body">
                    <span class="count_value">{{ $clientCount }}</span>
                    <span class="count_duration">Today</span>
                </div>
            </div>
        </div>
        <div class="count_info_single candidate_count_info">
            <div class="inner">
                <div class="count_info_header">
                    <div class="title"> Candidate Created</div>
                    <div class="dropdown">
                        <button class="icon-btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="{{ asset('images/ellipsis-menu.svg') }}" alt="menu">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                            @foreach($CountDurationForDashboard as $key => $value)
                            <a class="dropdown-item get_dashboard_count {{ $key === 0 ? 'active' : '' }}" href="javascript:;" data-type="1" data-time="{{ $value['time'] }}">{{ $value['title'] }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="count_info_body">
                    <span class="count_value">{{ $candidateCount }}</span>
                    <span class="count_duration">Today</span>
                </div>
            </div>
        </div>
        <div class="count_info_single application_count_info">
            <div class="inner">
                <div class="count_info_header">
                    <div class="title"> Application Created</div>
                    <div class="dropdown">
                        <button class="icon-btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="{{ asset('images/ellipsis-menu.svg') }}" alt="menu">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                            @foreach($CountDurationForDashboard as $key => $value)
                            <a class="dropdown-item get_dashboard_count {{ $key === 0 ? 'active' : '' }}" href="javascript:;" data-type="2" data-time="{{ $value['time'] }}">{{ $value['title'] }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="count_info_body">
                    <span class="count_value">{{ $applicationCount }}</span>
                    <span class="count_duration">Today</span>
                </div>
            </div>
        </div>
        <div class="count_info_single timesheet_count_info">
            <div class="inner">
                <div class="count_info_header">
                    <div class="title"> Timesheet Created</div>
                    <div class="dropdown">
                        <button class="icon-btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="{{ asset('images/ellipsis-menu.svg') }}" alt="menu">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                            @foreach($CountDurationForDashboard as $key => $value)
                            <a class="dropdown-item get_dashboard_count {{ $key === 0 ? 'active' : '' }}" href="javascript:;" data-type="4" data-time="{{ $value['time'] }}">{{ $value['title'] }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="count_info_body">
                    <span class="count_value">{{ $timesheetCount }}</span>
                    <span class="count_duration">Today</span>
                </div>
            </div>
        </div>
        <div class="count_info_single invoice_count_info">
            <div class="inner">
                <div class="count_info_header">
                    <div class="title"> Invoice Created</div>
                    <div class="dropdown">
                        <button class="icon-btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="{{ asset('images/ellipsis-menu.svg') }}" alt="menu">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                            @foreach($CountDurationForDashboard as $key => $value)
                            <a class="dropdown-item get_dashboard_count {{ $key === 0 ? 'active' : '' }}" href="javascript:;" data-type="3" data-time="{{ $value['time'] }}">{{ $value['title'] }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="count_info_body">
                    <span class="count_value">{{ $invoiceCount }}</span>
                    <span class="count_duration">Today</span>
                </div>
            </div>
        </div>
        
    </div>
    <!-- <div class="row">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
                <div class="card-header">Users</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $userCount ?? '' }}</h5>
                    <p class="card-text">Users</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-secondary mb-3" style="max-width: 18rem;">
                <div class="card-header">Todo</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $todoCount ?? '' }}</h5>
                    <p class="card-text">Todo</p>
                </div>
            </div>
        </div>
    </div> -->

    <figure class="highcharts-figure">
    <div id="TimesheetChart"></div>
    </figure>

    <figure class="highcharts-figure1">
    <div id="CategoryTypeList"></div>
    </figure>

</div>
@endsection
@section('custom_scripts')
<script src="{{ asset('js/admin/dashboard.js') }}"></script>
<script src="{{ asset('js/admin/timesheetChart.js') }}"></script>
<script src="{{ asset('js/admin/categoryTypeList.js') }}"></script>
@endsection