<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>@yield('title')</title>

    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <link href="{{  asset('css/full-slider.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    {{-- custome css --}}
    <link rel="stylesheet" href="{{ URL::asset('css/custom.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('css/bxslider.css') }}">

    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bxslider.min.js') }}"></script>

    {{-- FontAwesome 4.7.0.3--}}
    <link rel="stylesheet" href="{{ URL::asset('fontawesome/css/font-awesome.min.css') }}">

    <script>
      $(document).ready(function(){
        $('.slider').bxSlider({
          auto: true,
          autoControls: true,
          stopAutoOnClick: true,
          pager: true,
          slideWidth: 800
        });
      });
    </script>
    <style type="text/css">
      .img-responsive {
          margin: 0 auto;
      }
    </style>

  </head>

  <body>
  <div class="wrapper">
    @yield('content')
  </div>

    <!-- Bootstrap core JavaScript -->
    <script src="{{ asset('vendor/popper/popper.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>

  </body>

</html>
