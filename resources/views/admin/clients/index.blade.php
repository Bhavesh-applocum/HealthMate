@extends('layouts.__layout')
@section('title','clients')
@section('main_content')
<span id="fetch_data" data-url="{{ route('admin.clients.index') }}"></span>
<div class="box">
    <div class="dataTable-design">
        <div class="dataTable-title-wrapper">
            <div class="inner">
                <div class="left">
                    <h3 class="dataTable-title">Clients</h3>
                </div>
                <div class="right">
                    <div class="dataTable-search-wrapper">
                        <input type="text" placeholder="search table" id="dataTable-search">
                        <button class="dataTable-search-clear">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <a href="{{ route('admin.clients.create') }}" class="icon-btn icon-btn-lg ml-2 __shadow icon-btn-primary-purple icon-btn-hover-outline">
                        <i class="fa-solid fa-plus"></i>
                    </a>
                </div>
            </div>
        </div>
        @include('partials.client.__table')
    </div>
    <!-- make sidebar in rightside -->
    <div class="Fsidebar">
    
    <div class="inner">
        <button class="sidebarCloseButton icon-btn icon-btn-primary-blue icon-btn-md icon-btn __shadow-lg icon-btn-hover-outline no-bg close-btn">
            <i class="fas fa-times" aria-hidden="true"></i>
        </button>
            <div class="avatar">
                <img class="client_profile" src="https://bit.ly/3hLadSX" alt="">
            </div>
            <div class="name d-flex justify-content-center flex-column align-items-center pt-3">
                <h3 class="clientName ">John Doe</h3>
                <p class="clientEmail ">johndoe@gmail.com</p>
            </div>
            <div class="details w-100">
                <div class="phone d-flex w-100">
                <div>Phone No :</div>
                <p class="clientPhone">1234567890</p>
                </div>
                <div class="address d-flex w-100">
                <div>Address :</div>
                <p class="clientAddress">123, Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum.</p>
                </div>
            </div>
            <a href="#" class="editButton form-control btn-custom-primary-blue btn-hover-outline mw-200 w-100 m-auto text-center" tabindex="1">Edit</a>
        </div>
</div>
@endsection

@section('custom_scripts')
<script src="{{asset('js/admin/client/index.js')}}"></script>
@endsection