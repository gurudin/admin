<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title') {{ config('app.name', '') }}</title>

  <!-- Styles -->
  <link rel="stylesheet" type="text/css" href="{{ mix('css/admin.css', 'vendor/gurudin') }}">
  <link rel="stylesheet" href="https://cms-dev-admin.meme.chat/assets/33994a49/custom.css?v=1515407478">
  @yield('style')
</head>
<body>

<div class="container-full body">
  <div class="main_container">
    <div class="col-md-3 left_col menu_fixed" style="overflow: scroll;">
      <div class="left_col scroll-view">

        <div class="navbar nav_title" style="border: 0;">
          <a href="/" class="site_title"><img width="34px" height="34px" style="margin-top: -10px;" src="https://cms-dev-admin.meme.chat//images/logo_grey.png" alt=""> <span>MeMe Admin</span></a>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
@section('main-script')
  <script src="{{ mix('js/admin.js', 'vendor/gurudin') }}"></script>
@show

@section('script')
@show
</body>
</html>