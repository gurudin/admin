@extends('admin::layouts.blank')

@section('title')
Select
@endsection

@section('css')
@parent
  <link href="{{ mix('css/admin.css', 'vendor/gurudin') }}" rel="stylesheet">
  <style>
  body {
    background: #f2f2f2;
  }
  .color-warning {
    color: #8a6d3b;
  }
  </style>
@endsection

@section('content')
<div class="card" style="width: 45%; margin: 0 auto; margin-top: 5%;">

  @if (!empty($group_list))
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
  @else
  {{-- empty --}}
  <h5 class="card-header">
    You have successfully logged in as {{ Auth::user()->name }}
  </h5>

  <div class="card-body color-warning text-sm-left">
    <div><i class="fas fa-exclamation-triangle"></i> But it seems like you don't have any permission at this moment. Please contact your manager to grant permissions for the new account, then refresh this page.</div>
    <div style="margin-top:5px;"><i class="fas fa-exclamation-triangle"></i> 你已经成功登入系统，但看起来这个账号还没有任何权限，这是正常的。请联系管理者进行授权，完成后刷新本页即可。</div>
  </div>
  {{-- end empty --}}
  @endif

  <div class="card-footer text-muted text-right">
    © Administration 2018
  </div>
  
</div>
@endsection