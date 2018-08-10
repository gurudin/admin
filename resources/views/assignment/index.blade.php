@extends('admin::layouts.config')

@section('title')
{{__('admin::messages.assignment.assignment')}}
@endsection

@section('content')
<div class="view" id="main-app">
  <div class="col-12">
    <div class="row justify-content-between">
      <div class="col-4 h3">{{__('admin::messages.assignment.assignment')}}</div>

      <div class="col-4 text-right"></div>
    </div>
  </div>

  <div class="col-12" v-cloak>
    <table class="table table-hover">
      <thead class="thead-light">
        <tr>
          <th>#</th>
          <th>{{__('admin::messages.assignment.nick')}}</th>
          <th>{{__('admin::messages.assignment.email')}}</th>
          <th>{{__('admin::messages.assignment.group')}}</th>
          <th>{{__('admin::messages.common.action')}}</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="(user,inx) in userItem">
          <td>@{{user.id}}</td>
          <td>@{{user.name}}</td>
          <td>@{{user.email}}</td>
          <td>
            <p v-for="group in user.group">@{{group.name}} </p>
            <span v-if="!user.group" class="text-danger">(not set)</span>
          </td>
          <td>
            <a
              class="btn btn-info btn-sm"
              :href="'{{route('get.assignment.view')}}/' + user.id + '?group={{request()->group}}'"
              v-if="user.group">
              <i class="fas fa-eye"></i>
            </a>
          </td>
        </tr>
      </tbody>

    </table>
  </div>
</div>
@endsection

@section('script')
@parent
<script>
new Vue({
  el: '#main-app',
  data() {
    return {
      userItem: @json($user_item)
    };
  },
});
</script>
@endsection
