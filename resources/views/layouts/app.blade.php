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

  <div id="app">
    <nav class="navbar navbar-dark fixed-top bg-info flex-md-nowrap p-0 shadow">
      <a class="navbar-brand col-md-2 mr-0" href="{{route('get.welcome')}}" id="nav-top">{{config('app.name')}}</a>
      
      <div class="navbar-collapse">
        <nav class="navbar navbar-dark">
          <button class="navbar-toggler" type="button" id="navbar-toggler">
            <span class="navbar-toggler-icon"></span>
          </button>
        </nav>
      </div>

      <div class="dropdown">
        <button class="btn btn-info dropdown-toggle dropdown-toggle-bar" type="button">
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

      <div class="btn-group">
        <button type="button" class="btn bg-info text-white dropdown-toggle dropdown-toggle-bar">
          <i class="fas fa-user-tie"></i>
          {{Auth::user()->name}}
        </button>
        <div class="dropdown-menu dropdown-menu-bar dropdown-menu-right">
          <a class="dropdown-item" href="{{ route('logout') }}"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i> Logout
          </a>

          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
          </form>
        </div>
      </div>
      &nbsp;
    </nav>

    <div class="container-fluid">
      <div class="row">
        {{-- Left nav --}}
        <nav class="col-md-2 bg-light sidebar" id="gurudin-menu-bar" ref="menu">
          <div class="sidebar-sticky">

            <menu-tree
              :data-tree="{{ json_encode(Gurudin\Admin\Support\Helper::authMenu(Auth::user(), request('group', 0))) }}"
              :data-group="{{ request()->group }}"
              current-uri="{{ Route::current()->uri }}"></menu-tree>

          </div>
        </nav>
        {{-- Left nav end --}}

        {{-- Main --}}
        <main role="main" class="col-md-10 ml-sm-auto px-4" id="gurudin-main">
          @yield('content')
        </main>
        {{-- Main end --}}
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