@extends('admin::layouts.config')

@section('title')
Welcome
@endsection

@section('content')
<div class="jumbotron vertical-center">
  <h1>Welcome, {{ Auth::user()->name}}!</h1>

  <p class="lead">You have successfully logged in Admin.</p>

  <small><i class="fa fa-warning"></i> We log every actions and operations, please use with care.</small>
</div>
@endsection