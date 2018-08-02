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

        <div class="row col-12" v-cloak>
          <div class="col">
            <div class="form-group">
              <input type="text" class="form-control" v-model="searchKey.localRoute" placeholder="{{__('admin::messages.permission.search-for-routes')}}">
              <select multiple class="form-control" size="28" ref="select-local-route">
                <optgroup label="{{__('admin::messages.route.route')}}">
                  <option v-for="route in localRoutesData" :value="route.method+' '+route.name">@{{route.method.toUpperCase()}} @{{route.name}}</option>
                </optgroup>
              </select>
            </div>
          </div>

          <div class="col-2 text-center" style="margin-top: 10%;">
            <div class="btn-group-vertical">
              <button type="button" class="btn btn-success btn-lg" @click="addBacthRoutes"><i class="fas fa-chevron-right"></i></button>
              <button type="button" class="btn btn-danger btn-lg" @click="removeBacthRoutes"><i class="fas fa-chevron-left"></i></button>
            </div>
          </div>
          
          <div class="col">
            <div class="form-group">
              <input type="text" class="form-control" v-model="searchKey.route" placeholder="{{__('admin::messages.permission.search-for-routes')}}">
              <select multiple class="form-control" size="28" ref="select-route">
                <optgroup label="{{__('admin::messages.route.route')}}">
                  <option v-for="route in routesData" :value="route.method+' '+route.name">@{{route.method.toUpperCase()}} @{{route.name}}</option>
                </optgroup>
              </select>
            </div>
          </div>
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
      localRoutes: @json($local_routes),
      permissionItem: @json($routes),
      searchKey: {route: '', localRoute: ''},
      routeModel: {name: '', method: 'get'},
      validate: false,
    };
  },
  computed: {
    localRoutesData() {
      var keyWord = this.searchKey.localRoute && this.searchKey.localRoute.toLowerCase();
      var data = this.localRoutes;

      if (data) {
        data = data.filter(row => {
          var check = true;
          this.permissionItem.route.forEach(function(val) {
            if (row.method+row.name == val.method+val.name) {
              check = false;
            }
            if (row.name == val.name && val.method == 'any') {
              check = false;
            }
          });

          return check;
        });
      }
      
      data = data.filter(row => {
        return String(row.name).toLowerCase().indexOf(keyWord) > -1;
      });

      return data;
    },
    routesData() {
      var keyWord = this.searchKey.route && this.searchKey.route.toLowerCase();
      var data = this.permissionItem.route;

      data = data.filter(row => {
        return String(row.name).toLowerCase().indexOf(keyWord) > -1;
      });

      return data;
    },
    routeLength() {
      return this.permissionItem.route ? this.permissionItem.route.length : 0;
    }
  },
  methods: {
    addBacthRoutes(event) {
      var select_routes = $(this.$refs["select-local-route"]).val();
      if (select_routes.length == 0) {
        return false;
      }

      var routes = [];
      select_routes.forEach(row =>{
        let tmp = row.split(" ");
        routes.push({method: tmp[0], name: tmp[1]});
      });
      
      var _this = this;
      var $btn = $(event.currentTarget);
      $btn.loading('<i class="fas fa-spinner fa-spin"></i>');
      axios.post('{{route("post.route.create")}}', routes).then(function (response) {
        if (response.data.status) {
          routes.forEach(row =>{
            _this.permissionItem.route.unshift($.extend({}, row));
          });
        } else {
          alert(response.data.msg);
        }
        $btn.loading('reset');
      });
    },
    removeBacthRoutes(event) {
      var select_routes = $(this.$refs["select-route"]).val();
      if (select_routes.length == 0) {
        return false;
      }

      var routes = [];
      select_routes.forEach(row =>{
        let tmp = row.split(" ");
        routes.push({method: tmp[0], name: tmp[1]});
      });

      var _this = this;
      var $btn = $(event.currentTarget);
      $btn.loading('<i class="fas fa-spinner fa-spin"></i>');
      axios.delete('{{route("delete.route.destroy")}}', {
        data: routes
      }).then(function (response) {
        if (response.data.status) {
          routes.forEach(row =>{
            for (let i = _this.permissionItem.route.length-1; i >= 0; i--) {
              if (row.method+row.name == _this.permissionItem.route[i].method+_this.permissionItem.route[i].name) {
                _this.permissionItem.route.splice(i, 1);
              }
            }
          });
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
