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
  <style>
    body {
  font-family: "Helvetica Neue", Roboto, Arial, "Droid Sans", sans-serif;
  font-size: 13px;
  font-weight: 400;
  line-height: 1.471;
  background-color: white;
}
a:hover{ text-decoration: none; }
.gurudin-main{
  margin: 0;
  padding: 0;
}
.left-col{
  background: #333d54;
  width: 230px;
  color: #fff;
  padding: 0;
  margin: 0;
  height: 100%;
  position: fixed;
  z-index: 10;
}
.nav_title{
  width: 230px;
  float: left;
  background: #333d54;
  border-radius: 0;
  height: 52px;
  padding-left: 10px;
}
.site_title {
  text-overflow: ellipsis;
  overflow: hidden;
  font-weight: 400;
  font-size: 22px;
  width: 100%;
  color: #ECF0F1 !important;
  margin-left: 0 !important;
  line-height: 59px;
  display: block;
  height: 55px;
  margin: 0;
}

.main_menu_side{
  padding: 0;
}
.menu_section {
  margin-bottom: 35px;
}
.menu_section h3 {
  padding-left: 15px;
  color: #fff;
  text-transform: uppercase;
  letter-spacing: .5px;
  font-weight: bold;
  font-size: 11px;
  margin-bottom: 0;
  margin-top: 0;
  text-shadow: 1px 1px #000;
}
.menu_section>ul {
  margin-top: 10px;
}
.nav.side-menu>li {
  width: 100%;
  position: relative;
  display: block;
  cursor: pointer;
}

.nav.side-menu>li>a, .nav.child_menu>li>a {
  color: #E7E7E7;
  font-weight: 500;
}
.nav.side-menu>li>a {
  margin-bottom: 6px;
}
.nav>li>a {
  position: relative;
  display: block;
  padding: 13px 15px 12px;
}
.main_menu span.fas {
  float: right;
  text-align: center;
  margin-top: 5px;
  font-size: 10px;
  min-width: inherit;
  color: #C4CFDA;
}
.main_menu .fas {
  width: 26px;
  opacity: .99;
  display: inline-block;
  font-style: normal;
  font-weight: normal;
  font-size: 18px;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}
.nav.side-menu>li.current-page, .nav.side-menu>li.active {
  border-right: 5px solid #1ABB9C;
}
.nav.side-menu>li.active>a {
  text-shadow: rgba(0, 0, 0, 0.25) 0 -1px 0;
  background: linear-gradient(#334556, #2C4257), #333d54;
  box-shadow: rgba(0, 0, 0, 0.25) 0 1px 0, inset rgba(255, 255, 255, 0.16) 0 1px 0;
}
.nav.child_menu {
  display: none;
}
.nav.child_menu li {
  padding-left: 36px;
  width: 100%;
}
.nav.child_menu li:hover {
  background-color: rgba(255, 255, 255, 0.06);
}
.nav.child_menu>li>a {
  color: rgba(255, 255, 255, 0.75);
  font-size: 12px;
  padding: 9px;
}
.nav.side-menu>li>a, .nav.child_menu>li>a {
  color: #E7E7E7;
  font-weight: 500;
}
.nav>li>a {
  position: relative;
  display: block;
  padding: 13px 15px 12px;
}

.right-col{
  float: left;
  margin-left: 230px;
  width: 100%;
}
.right-col .navbar {
  padding: 0;
  height: 53px;
}

#gurudin-nav {
  position: fixed;
  background-color: white;
  z-index: 1;
}
#gurudin-nav .dropdown-toggle-bar{
  font-size: 14px;
  background-color: white;
}
  </style>
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