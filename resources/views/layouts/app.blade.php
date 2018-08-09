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
  @yield('style')
</head>
<body>

<div class="container-full row gurudin-main">
  <div class="col-3 left-col" style="overflow: scroll;">
    
    <div class="navbar nav_title" style="border: 0;">
      <a href="{{ route('get.welcome') }}" class="site_title">
        <img width="34px" height="34px" style="margin-top: -10px;" src="{{ config('admin.logo_uri') }}">
        <span id="site-name">{{ config('app.name') }}</span>
      </a>
    </div>

    {{-- sidebar menu --}}
    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">


      <div class="menu_section" id="menu-section">
        <h3>&nbsp;</h3>
        
        {{-- Menu item --}}

      </div>

    </div>
    {{-- /sidebar menu --}}

  </div>

  <div class="right-col">

    {{-- Header nav --}}
    <nav class="navbar" id="gurudin-nav" style="display: none;">
      <div class="nav toggle text-muted">
        <button type="button" class="btn navbar-toggler" id="gurudin-navbar-toggle">
          <i class="fas fa-bars text-muted"></i>
        </button>
      </div>

      <form class="form-inline" id="logout-form" action="{{ route('logout') }}" method="POST">
        @csrf

        <div class="dropdown">
          <button class="btn dropdown-toggle text-muted dropdown-toggle-bar" type="button">
            <i class="fas fa-globe-americas"></i>
            
            @foreach (Gurudin\Admin\Support\Helper::authGroup(Auth::user()) as $group)
              @if ($group['id'] == request()->group)
              {{ strtoupper($group['name']) }}
              @endif
            @endforeach
          </button>

          <div class="dropdown-menu dropdown-menu-bar dropdown-menu-right">
            @foreach (Gurudin\Admin\Support\Helper::authGroup(Auth::user()) as $group)
              <a class="dropdown-item" href="{{ url()->current() . '?group=' . $group['id'] }}">{{ strtoupper($group['name']) }}</a>
            @endforeach
          </div>
        </div>

        &nbsp;

        <div class="dropdown">
          <button type="button" class="btn text-muted dropdown-toggle dropdown-toggle-bar">
            <i class="fas fa-user-tie"></i> {{Auth::user()->name}}
          </button>

          <div class="dropdown-menu dropdown-menu-bar dropdown-menu-right" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item" href="{{ route('logout') }}"
              onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="fas fa-sign-out-alt"></i> Logout
            </a>
          </div>
        </div>
        &nbsp;
      </form>
    </nav>
    {{-- /Header nav end --}}

    {{-- Main --}}
    <main role="main" class="col-auto ml-sm-auto" id="gurudin-main">
      @yield('content')
    </main>
    {{-- /Main end --}}

  </div>
</div>


<!-- Scripts -->
@section('main-script')
  <script src="{{ mix('js/admin.js', 'vendor/gurudin') }}"></script> 
@show

<script>
  $.init(@json(Gurudin\Admin\Support\Helper::authMenu(Auth::user(), request("group", 0))), {{ request("group", 0) }});
</script>

@section('script')
@show
</body>
</html>