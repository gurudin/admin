@extends('admin::layouts.config')

@section('title')
{{isset(request()->id) ? __('admin::messages.menu.update-menu') : __('admin::messages.menu.create-menu')}}
@endsection

@section('content')
<div class="view" id="main-app">
  <div class="col-12">
    <div class="row justify-content-between">
      <div class="col-4 h3">{{isset(request()->id) ? __('admin::messages.menu.update-menu') : __('admin::messages.menu.create-menu')}}</div>

      <div class="col-4 text-right">
        <a href="{{url()->previous()}}" class="btn col-4 btn-light">
          <i class="fa fa-arrow-left"></i> {{__('admin::messages.common.back')}}
        </a>
      </div>
    </div>
  </div>

  <div class="col-12" v-cloak>
    <form>
      <div class="form-group">
        <label>{{__('admin::messages.menu.title')}}</label>
        <input
          type="text"
          class="form-control"
          :class="{'is-invalid':validate && menuModel.title==''}"
          v-model="menuModel.title"
          placeholder="{{__('admin::messages.menu.title')}}">
      </div>

      <div class="form-group">
        <label>{{__('admin::messages.menu.parent')}}</label>
        <v-select placeholder="{{__('admin::messages.menu.parent')}}" :options="menus" label="title" v-model="parentModel">
          <template slot="option" slot-scope="option" class="list-group">
              <h6><i :class="repIcion(option.data)"></i> @{{option.title}}</h6>
              <p class="text-muted">
                @{{option.parent_name ? option.parent_name : 'null'}} | @{{option.route ? option.route : 'null'}}
              </p>
          </template>
        </v-select>
      </div>

      <div class="form-group">
        <label>{{__('admin::messages.menu.route')}}</label>
        <v-select placeholder="{{__('admin::messages.menu.route')}}" :options="routesData" label="name" v-model="routeModel"></v-select>
      </div>

      <div class="form-group">
        <label>{{__('admin::messages.menu.order')}}</label>
        <input
          type="text"
          class="form-control"
          v-model="menuModel.order"
          placeholder="{{__('admin::messages.menu.order')}}">
      </div>

      <div class="form-group">
        <label>{{__('admin::messages.menu.data')}}</label>
        <textarea class="form-control" rows="5" placeholder="{{__('admin::messages.menu.data')}}" v-model="menuModel.data"></textarea>
      </div>

      <button type="button" class="btn btn-success" @click="save">{{__('admin::messages.common.save')}}</button>
    </form>
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
      menus: @json($menus),
      routes: @json($routes),
      menuModel: @json($curr_menu),
      parentModel: null,
      routeModel: null,
      validate: false,
    };
  },
  computed: {
    routesData() {
      var data = this.routes;
      data = data.filter(row =>{
        return row.method == 'get' || row.method == 'any' ? true : false;
      });

      return data;
    },
  },
  created() {
    var _this = this;

    if (_this.menuModel.parent) {
      _this.menus.filter(row =>{
        if (row.id == _this.menuModel.parent) {
          _this.parentModel = row;
        }
      });
    }
    
    if (_this.menuModel.route) {
      _this.routes.filter(row =>{
        if (row.name == _this.menuModel.route) {
          _this.routeModel = row;
        }
      });
    }
    
  },
  methods: {
    repIcion(data) {
      if (!data) {
        return '';
      }
      let json = eval("(" + data + ")");

      return json.icon;
    },
    save(event) {
      this.validate = true;
      if (this.menuModel.title == "") {
        return false;
      }
      this.parentModel ? this.menuModel.parent = this.parentModel.id : null;
      this.routeModel ? this.menuModel.route = this.routeModel.name: '';
      
      $btn = $(event.target);
      $btn.loading();
      
      
      if (this.menuModel.id == 0) {
        axios.post('{{route("post.menu.create")}}', this.menuModel).then(function (response) {
          if (response.data.status) {
            window.location = '{{url()->previous()}}';
          } else {
            alert(response.data.msg);
            $btn.loading("reset");
          }
        });
      } else {
        axios.put('{{route("put.menu.update")}}', this.menuModel).then(function (response) {
          if (response.data.status) {
            window.location = '{{url()->previous()}}';
          } else {
            alert(response.data.msg);
            $btn.loading("reset");
          }
        });
      }
    }
  }
});
</script>
@endsection
