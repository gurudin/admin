@extends('admin::layouts.config')

@section('title')
{{__('admin::messages.menu.menu')}}
@endsection

@section('content')
<div class="view" id="main-app">
  <div class="col-12">
    <div class="row justify-content-between">
      <div class="col-4 h3">{{__('admin::messages.menu.menu')}}</div>

      <div class="col-4 text-right">
        <a href="{{route('get.menu.create', ['group' => request()->group])}}" class="btn col-4 btn-success">
          {{__('admin::messages.common.create')}}
        </a>
      </div>
    </div>
  </div>

  <div class="col-12">
    <table class="table table-hover">
      <thead class="thead-light">
        <tr>
          <th scope="col">#</th>
          <th scope="col">{{__('admin::messages.menu.title')}}</th>
          <th scope="col">{{__('admin::messages.menu.order')}}</th>
          <th scope="col">{{__('admin::messages.menu.route')}}</th>
          <th scope="col">{{__('admin::messages.menu.parent')}}</th>
          <th scope="col">{{__('admin::messages.menu.data')}}</th>
          <th scope="col">{{__('admin::messages.common.action')}}</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="(item,inx) in menuItem" v-cloak>
          <th scope="row">@{{item.id}}</th>
          <td>@{{item.title}}</td>
          <td>@{{item.order}}</td>
          <td>@{{item.route ? item.route : '(not set)'}}</td>
          <td>@{{item.parent_name ? item.parent_name : '(not set)'}}</td>
          <td>@{{item.data ? item.data : '(not set)'}}</td>
          <td>
            <a :href="'{{route('get.menu.update')}}/' + item.id + '?group={{request()->group}}'" class="btn btn-warning text-white btn-sm">
              <i class="fas fa-edit"></i>
            </a>
            <button type="button" class="btn btn-danger btn-sm" @click="deleteMenu($event, inx, item)"><i class="fas fa-trash-alt"></i></button>
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
      menuItem: @json($menus),
    };
  },
  methods: {
    deleteMenu(event, inx, item) {
      if (!confirm('{{__('admin::messages.common.are-you-sure-to-delete-this-item')}}')) {
        return false;
      }

      var $btn = $(event.currentTarget);
      $btn.loading('<i class="fas fa-spinner fa-spin"></i>');
      
      var _this = this;
      axios.delete('{{route("delete.menu.destroy")}}', { data: {id: item.id} }).then(function (response) {
        if (response.data.status) {
          _this.menuItem.splice(inx, 1);
        } else {
          alert(response.data.msg);
        }
        $btn.loading("reset");
      });
    }
  }
});
</script>
@endsection
