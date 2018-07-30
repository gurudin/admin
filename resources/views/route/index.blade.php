@extends('admin::layouts.config')

@section('title')
{{__('admin::messages.route.route')}}
@endsection

@section('content')
<div class="view" id="main-app">
  <div class="col-12">
    <div class="row justify-content-between">
      <div class="col-4 h3">{{__('admin::messages.route.route')}}</div>

      <div class="col-4 text-right"></div>
    </div>
  </div>

  <div class="col-12">
    <div class="card h-100">
      <div class="card-body">
        
        <div class="input-group mb-3">
          <div class="input-group-append">
            <select class="form-control">
              <option value="get">GET</option>
              <option value="post">POST</option>
              <option value="put">PUT</option>
              <option value="delete">DELETE</option>
              <option value="any">ANY</option>
            </select>
          </div>
          <input type="text" class="form-control" placeholder="{{__('admin::messages.route.input-routing-address')}}" :class="{'is-invalid':validate && (routeModel.name=='' || routeModel.name[0] != '/')}" v-model.trim="routeModel.name">
          <div class="input-group-append">
            <button
              class="btn btn-outline-success"
              type="button"
              @click="addRoute">{{__('admin::messages.route.add-route')}}</button>
          </div>
        </div>

        <div class="list-group" style="height: 600px; overflow: scroll;">

          <li class="list-group-item list-group-item-action" v-for="(item,inx) in permissionItem.route" v-cloak>
            <span class="badge badge-secondary bg-primary">@{{item.method.toUpperCase()}}</span> @{{item.name}}
            <button type="button" class="btn btn-danger btn-sm float-right" @click="remove($event, inx, item)">
              <i class="fas fa-trash-alt"></i>
            </button>
          </li>

        </div>
      </div>

      <div class="card-footer text-muted">
        Total {{count($routes['route'])}}
      </div>
    </div>

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
      permissionItem: @json($routes),
      routeModel: {name: '', method: 'get'},
      validate: false,
    };
  },
  computed: {
    routeLength() {
      return this.permissionItem.route ? this.permissionItem.route.length : 0;
    }
  },
  methods: {
    addRoute(event) {
      this.validate = true;
      if (this.routeModel.name == '' || this.routeModel.name[0] != '/') {
        return false;
      }
      let isRepet = 1;
      this.permissionItem.route.forEach(row =>{
        if (row.name == this.routeModel.name && row.method == this.routeModel.method) {
          isRepet = 0;
        }
      });

      if (isRepet == 0) {
        alert('{{__('admin::messages.route.route-repetition')}}');
        return false;
      }

      var _this = this;
      var $btn = $(event.currentTarget);

      $btn.loading('<i class="fas fa-spinner fa-spin"></i>');

      axios.post('{{route("post.route.create")}}', this.routeModel).then(function (response) {
        if (response.data.status) {
          _this.permissionItem.route.unshift($.extend({}, _this.routeModel));
        } else {
          alert(response.data.msg);
        }
        $btn.loading('reset');
      });
    },
    remove(event, inx, item) {
      if (!confirm('{{__('admin::messages.common.are-you-sure-to-delete-this-item')}}')) {
        return false;
      }

      var _this = this;
      var $btn = $(event.currentTarget);
      $btn.loading('<i class="fas fa-spinner fa-spin"></i>');

      axios.delete('{{route("delete.route.destroy")}}', {
        data: {name: item.name,
        method: item.method}
      }).then(function (response) {
        if (response.data.status) {
          _this.permissionItem.route.splice(inx, 1);
        } else {
          alert(response.data.msg);
        }
        $btn.loading('reset');
      });
    },
  }
})
</script>
@endsection
