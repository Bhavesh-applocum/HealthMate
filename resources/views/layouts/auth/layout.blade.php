<!DOCTYPE html>
<html>

<head>

  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <title>@yield('title')</title>
  <link rel="icon" type="image/x-icon" href="Healthmate-black.ico">
  @include('includes.css')
</head>

<body>
  @include('global.show_session')
  @yield('main_content')
</body>

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/validations/index.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>

@yield('custom_scripts')
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

</html>