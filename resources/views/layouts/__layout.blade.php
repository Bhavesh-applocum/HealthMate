<!DOCTYPE html>
<html>

<head>

  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <title>@yield('title')</title>
  @include('includes.css')
  @yield('style')
</head>
@php 

@endphp
<body class="" data-login-type="{{Session::get('user_type')}}">
  @include('includes.header')
  @include('global.show_session')
  <main>
    <div class="left_main">
      @include('includes.sidebar')
    </div>
    <div class="right_main">
      @yield('main_content')
    </div>
  </main>
  @include('includes.delete_confirmation_model')

  <script src="{{ asset('js/jquery.min.js') }}"></script>
  <script>
    var login_type = $('body').data('login-type');
  </script>
  <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('js/validations/index.js') }}"></script>
  <script src="{{ asset('js/fontawesome.js') }}"></script>
  <script src="{{ asset('js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('js/anime.min.js') }}"></script>
  <script src="{{ asset('js/dataTables.min.js') }}"></script>
  <script src="{{ asset('js/select2.full.js') }}"></script>
  <script src="{{ asset('js/modal.js') }}"></script>
  <script src="{{ asset('js/common.js') }}"></script>
  
  @yield('custom_scripts')
  <script src="{{ asset('js/dataTable-custom.js') }}"></script>
  <script src="{{ asset('js/validations/after_validation.js') }}"></script>
  <script>
    setTimeout(function() {
      $('.session_message_wrapper').addClass('_hide').animate({
        opacity: 0,
      }, 1000, function() {
        $(this).remove();
      });
    }, 2000);
  </script>
</body>
</html>