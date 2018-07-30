@extends('admin::layouts.blank')

@section('title')
Register
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
<div class="col-8 offset-md-2" style="margin-top: 10%;">
  <div class="card bg-light">
    <div class="card-header bg-dark text-white">
      <div class="row justify-content-between">
        <div class="col-8 h4">创建账号</div>

        <div class="col-4 text-right">
          <a href="{{ route('get.auth.login') }}" class="btn-link text-info"><i class="fas fa-sign-in-alt"></i> <b>登陆?</b></a>
        </div>
      </div>
    </div>
    
    <div class="card-body">
      <form method="POST" action="{{ route('post.auth.register') }}">
        @csrf

        <div class="form-group row">
          <label class="col-sm-3 col-form-label text-right">用户名</label>
          <div class="col-sm-8">
            <input type="text"
              class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
              name="name"
              placeholder="用户名"
              required autofocus>

            @if ($errors->has('name'))
              <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('name') }}</strong>
              </span>
            @endif
          </div>
        </div>

        <div class="form-group row">
          <label class="col-sm-3 col-form-label text-right">邮箱地址</label>
          <div class="col-sm-8">
            <input type="email"
              class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
              name="email"
              placeholder="邮箱地址"
              required>

            @if ($errors->has('email'))
              <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('email') }}</strong>
              </span>
            @endif
          </div>
        </div>

        <div class="form-group row">
          <label class="col-sm-3 col-form-label text-right">密码</label>
          <div class="col-sm-8">
            <input type="password"
              class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
              name="password"
              placeholder="密码"
              required>
            
            @if ($errors->has('password'))
              <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('password') }}</strong>
              </span>
            @endif
          </div>
        </div>

        <div class="form-group row">
          <label class="col-sm-3 col-form-label text-right">确认密码</label>
          <div class="col-sm-8">
            <input type="password"
              class="form-control"
              name="c_password"
              placeholder="确认密码"
              required>
          </div>
        </div>

        <div class="form-group row">
          <div class="col-sm-8 offset-sm-3">
            <div class="form-group">
              <div class="form-check">
                <input class="form-check-input"
                  type="checkbox"
                  id="agree"
                  required>
                <label class="form-check-label" for="agree">
                  创建账号，既同意服务条款。
                </label>
              </div>
            </div>
          </div>
        </div>

        <div class="form-group row">
          <div class="col-sm-8 offset-sm-3">
            <button type="submit" class="btn btn-success btn-lg"><i class="fas fa-user-plus"></i> 创建账号</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection