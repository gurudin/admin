@extends('admin::layouts.config')

@section('title')
{{$name}}
@endsection

@section('content')
<div class="view" id="main-app">
  <div class="col-12">
    <div class="row justify-content-between">
      <div class="col-8 h3">{{__('admin::messages.permission.permission')}}: {{$name}}</div>

      <div class="col-4 text-right">
        <a href="{{url()->previous()}}" class="btn col-4 btn-light">
          <i class="fa fa-arrow-left"></i> {{__('admin::messages.common.back')}}
        </a>
      </div>
    </div>
  </div>

  <div class="row col-12" v-cloak>
    <div class="col">
      <div class="form-group">
        <input type="text" class="form-control" v-model="searchKey.route" placeholder="{{__('admin::messages.permission.search-for-routes')}}">
        <select multiple class="form-control" size="20" ref="select-route">
          <optgroup label="{{__('admin::messages.route.route')}}">
            <option v-for="route in routeData" :value="route.method+' '+route.name">@{{route.method.toUpperCase()}} @{{route.name}}</option>
          </optgroup>
        </select>
      </div>
    </div>

    <div class="col-2 text-center" style="margin-top: 10%;">
      <div class="btn-group-vertical">
        <button type="button" class="btn btn-success btn-lg" @click="addRoutes"><i class="fas fa-chevron-right"></i></button>
        <button type="button" class="btn btn-danger btn-lg" @click="removeRoutes"><i class="fas fa-chevron-left"></i></button>
      </div>
    </div>

    <div class="col">
      <div class="form-group">
        <input type="text" class="form-control" v-model="searchKey.permission" placeholder="{{__('admin::messages.permission.search-for-assigned')}}">
        <select multiple class="form-control" size="20" ref="select-assigned">
          <optgroup label="{{__('admin::messages.route.route')}}">
            <option v-for="route in permissionRouteData" :value="route.method+' '+route.child">@{{route.method.toUpperCase()}} @{{route.child}}</option>
          </optgroup>
        </select>
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
      routeItem: @json($items['route']),
      permissionRouteItem: @json($child_items),
      name: '{{$name}}',
      searchKey: { route: "", permission: "" }
    };
  },
  computed: {
    permissionRouteData() {
      var keyWord = this.searchKey.permission && this.searchKey.permission.toLowerCase();
      var data = this.permissionRouteItem;
      if (!keyWord) {
        return data;
      }

      data = data.filter(row => {
        return (
          String(row.child)
            .toLowerCase()
            .indexOf(keyWord) > -1
        );
      });

      return data;
    },
    routeData() {
      var keyWord = this.searchKey.route && this.searchKey.route.toLowerCase();
      var data = this.routeItem;
      
      if (data) {
        data = data.filter(row => {
          var check = true;
          this.permissionRouteItem.forEach(function(val) {
            if (row.method+row.name == val.method+val.child) {
              check = false;
            }
          });

          return check;
        });
      }

      if (!keyWord) {
        return data;
      }

      data = data.filter(row => {
        return (
          String(row.name)
            .toLowerCase()
            .indexOf(keyWord) > -1
        );
      });

      return data;
    }
  },
  methods: {
    addRoutes(event) {
      var select_routes = $(this.$refs["select-route"]).val();
      if (select_routes.length == 0) {
        return false;
      }

      var childs = [];
      select_routes.forEach(row =>{
        let tmp = row.split(" ");
        childs.push({method: tmp[0], child: tmp[1]});
      });

      var _this = this;
      var $btn = $(event.currentTarget);
      $btn.loading('<i class="fas fa-spinner fa-spin"></i>');
      axios.post('{{route("post.permission.batchRouteChild")}}', {
        parent: _this.name,
        childs: childs
      }).then(function (response) {
        if (response.data.status) {
          select_routes.forEach(row => {
            let tmp = row.split(" ");
            _this.permissionRouteItem.push({
              parent: _this.name,
              method: tmp[0],
              child: tmp[1]
            });
          });
        } else {
          alert(res.body.msg);
        }
        $btn.loading("reset");
      });
    },
    removeRoutes(event) {
      var select_assigned = $(this.$refs["select-assigned"]).val();
      if (select_assigned.length == 0) {
        return false;
      }

      var childs = [];
      select_assigned.forEach(row =>{
        let tmp = row.split(" ");
        childs.push({method: tmp[0], child: tmp[1]});
      });

      var _this = this;
      var $btn = $(event.currentTarget);
      $btn.loading('<i class="fas fa-spinner fa-spin"></i>');
      axios.delete('{{route("delete.permission.batchRouteChild")}}', { data: {
        parent: _this.name,
        childs: childs
      }}).then(function (response) {
        if (response.data.status) {
          for (let i = _this.permissionRouteItem.length-1; i >= 0; i--) {
            if (select_assigned.indexOf(_this.permissionRouteItem[i]['method'] + ' ' + _this.permissionRouteItem[i]['child']) > -1) {
              _this.permissionRouteItem.splice(i, 1);
            }
          }
        } else {
          alert(res.body.msg);
        }
        $btn.loading("reset");
      });
    }
  }
});
</script>
@endsection
