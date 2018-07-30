@extends('admin::layouts.blank')

@section('title')
Login
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
        <div class="col-8 h4">登陆你的账号</div>

        <div class="col-4 text-right">
          <a href="{{ route('get.auth.register') }}" class="btn-link text-info"><b>注册?</b></a>
        </div>
      </div>
    </div>
    
    <div class="card-body">
      <form method="POST" action="{{ route('post.auth.login') }}">
        @csrf
        <div class="form-group row">
          <label class="col-sm-3 col-form-label text-right">邮箱地址</label>
          <div class="col-sm-8">
            <input type="email"
              class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
              placeholder="邮箱地址"
              value="4008353@qq.com"
              name="email"
              required autofocus>

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
            value="gaoxiang"
            name="password"
            placeholder="密码">

            @if ($errors->has('password'))
              <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('password') }}</strong>
              </span>
            @endif
          </div>
        </div>
        <div class="form-group row">
          <div class="col-sm-8 offset-sm-3">
            <div class="form-group">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember">
                <label class="form-check-label" for="remember">
                  记住我
                </label>
              </div>
            </div>
          </div>
        </div>

        <div class="form-group row">
          <div class="col-sm-8 offset-sm-3">
            <button type="submit" class="btn btn-lg btn-primary">登陆</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection