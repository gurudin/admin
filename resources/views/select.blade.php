@extends('admin::layouts.blank')

@section('title')
Select
@endsection

@section('css')
  <link href="{{ mix('css/admin.css', 'vendor/gurudin') }}" rel="stylesheet">
  <style>
  body {
    background: #f2f2f2;
  }
  </style>
@endsection

@section('content')
<div class="card w-75" style="margin: 0 auto; margin-top: 10%;">
  <h5 class="card-header">
    Select a group before continue
  </h5>

  <div class="card-body">
    <div class="list-group text-center">
      @foreach ($group_list as $group)
        <a href="{{ route('get.welcome', ['group' => $group['id']]) }}" class="list-group-item list-group-item-action">{{ $group['name'] }}</a>
      @endforeach
    </div>
  </div>

  <div class="card-footer text-muted text-right">
    © Administration 2018
  </div>
  
</div>
@endsection