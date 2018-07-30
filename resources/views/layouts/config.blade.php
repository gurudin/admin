{{-- extends blade --}}
@extends(config('admin.extends_blade'))

{{-- Pages title --}}
@section('title')
  @yield('title')
@endsection

{{-- Styles & resource file --}}
@section('style')
  <link rel="stylesheet" type="text/css" href="{{ mix('css/admin.css', 'vendor/gurudin') }}">

  <style>
    [v-cloak] {
      display: none !important;
    }
  </style>
@endsection

{{-- Content --}}
@section('content')
  @yield('content')
@endsection

@section('script')
  <script src="{{ mix('js/admin.js', 'vendor/gurudin') }}"></script>
@endsection
