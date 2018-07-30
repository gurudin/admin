@extends('admin::layouts.config')

@section('title')
{{$name}}
@endsection

@section('content')
<div class="view" id="main-app" v-cloak>
  <div class="col-12">
    <div class="row justify-content-between">
      <div class="col-8 h3">{{__('admin::messages.group.group')}}: @{{groupInfo.name}}</div>

      <div class="col-4 text-right">
        <a href="{{url()->previous()}}" class="btn col-4 btn-light">
          <i class="fa fa-arrow-left"></i> {{__('admin::messages.common.back')}}
        </a>
      </div>
    </div>
  </div>

  <div class="col-12">
    <div class="card">
      <h5 class="card-header text-muted"><i class="fas fa-users"></i> {{__('admin::messages.group.users-to-group')}}</h5>
      <div class="card-body">

        <div class="row">
          <div class="col">
            <div class="form-group">
              <input type="text" class="form-control" v-model="userToGroupKey.user" placeholder="{{__('admin::messages.group.search-for-user')}}">
              <select multiple class="form-control" size="20" ref="select-user">
                <optgroup label="{{__('admin::messages.group.user')}}">
                  <option v-for="(item,inx) in userItemData" :value="item.id">@{{item.name}} (@{{item.email}})</option>
                </optgroup>
              </select>
            </div>
          </div>

          <div class="col-2 text-center" style="margin-top: 10%;">
            <div class="btn-group-vertical">
              <button type="button" class="btn btn-success btn-lg" @click="addUserChild"><i class="fas fa-chevron-right"></i></button>
              <button type="button" class="btn btn-danger btn-lg" @click="removeUserChild"><i class="fas fa-chevron-left"></i></button>
            </div>
          </div>

          <div class="col">
            <div class="form-group">
              <input type="text" class="form-control" v-model="userToGroupKey.group" placeholder="{{__('admin::messages.group.search-for-user')}}">
              <select multiple class="form-control" size="20" ref="select-user-group">
                <optgroup label="{{__('admin::messages.group.user')}}">
                  <option v-for="(item,inx) in userChildItemData" :value="item.id">@{{item.name}} (@{{item.email}})</option>
                </optgroup>
              </select>
            </div>
          </div>
        </div>
        
      </div>
    </div>
    <br><br>

    <div class="card">
      <h5 class="card-header text-muted"><i class="fas fa-user"></i> {{__('admin::messages.group.permissions-roles-to-group')}}</h5>
      <div class="card-body">
        
        <div class="row">
          <div class="col">
            <div class="form-group">
              <input type="text" class="form-control" v-model="roleTogroupKey.item" placeholder="{{__('admin::messages.group.search-for-user')}}">
              <select multiple class="form-control" size="20" ref="select-item-child">
                <optgroup label="{{__('admin::messages.group.role')}}">
                  <option v-for="(item,inx) in roleItemData">@{{item.name}}</option>
                </optgroup>

                <optgroup label="{{__('admin::messages.group.permission')}}">
                  <option v-for="(item,inx) in itemPermissionData">@{{item.name}}</option>
                </optgroup>
              </select>
            </div>
          </div>

          <div class="col-2 text-center" style="margin-top: 10%;">
            <div class="btn-group-vertical">
              <button type="button" class="btn btn-success btn-lg" @click="addItemChild"><i class="fas fa-chevron-right"></i></button>
              <button type="button" class="btn btn-danger btn-lg" @click="removeItemChild"><i class="fas fa-chevron-left"></i></button>
            </div>
          </div>

          <div class="col">
            <div class="form-group">
              <input type="text" class="form-control" v-model="roleTogroupKey.group" placeholder="{{__('admin::messages.group.search-for-user')}}">
              <select multiple class="form-control" size="20" ref="select-item-child-group">
                <optgroup label="{{__('admin::messages.group.role')}}">
                  <option v-for="(item,inx) in itemChildItemData.role">@{{item.name}}</option>
                </optgroup>

                <optgroup label="{{__('admin::messages.group.permission')}}">
                  <option v-for="(item,inx) in itemChildItemData.permission">@{{item.name}}</option>
                </optgroup>
              </select>
            </div>
          </div>
        </div>

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
      userItem: @json($users),
      userChildItem: @json($group_children['users']),
      itemChildItem: @json($group_children['items']),
      itemPermission: @json($item_permission),
      roleItem: @json($item_role),
      groupInfo: {id: '{{$id}}', name: '{{$name}}'},
      userToGroupKey: {user: '', group: ''},
      roleTogroupKey: {item: '', group: ''},
    };
  },
  computed: {
    userItemData() {
      var keyWord = this.userToGroupKey.user && this.userToGroupKey.user.toLowerCase();
      var data = [];
      data = this.userItem.filter(user =>{
        var isExist = true;
        this.userChildItem.forEach(child =>{
          if (user.id == child.child) {
            isExist = false;
          }
        });

        return isExist;
      });

      if (!keyWord) {
        return data;
      }

      data = data.filter(row => {
        return (
          String(row.name).toLowerCase().indexOf(keyWord) > -1
          || String(row.email).toLowerCase().indexOf(keyWord) > -1
        );
      });

      return data;
    },
    userChildItemData() {
      var keyWord = this.userToGroupKey.group && this.userToGroupKey.group.toLowerCase();

      if (!this.userChildItem.length > 1) {
        return [];
      }

      var data = [];
      this.userItem.forEach(user =>{
        this.userChildItem.forEach(child =>{
          if (user.id == child.child) {
            data.push(user);
          }
        });
      });

      if (!keyWord) {
        return data;
      }

      data = data.filter(row => {
        return (
          String(row.name).toLowerCase().indexOf(keyWord) > -1
          || String(row.email).toLowerCase().indexOf(keyWord) > -1
        );
      });

      return data;
    },
    itemPermissionData() {
      var keyWord = this.roleTogroupKey.item && this.roleTogroupKey.item.toLowerCase();
      if (!this.itemPermission) {
        return [];
      }

      var data = [];
      var _this = this;
      data = this.itemPermission.filter(row =>{
        var isExist = true;
        _this.itemChildItem.forEach(item =>{
          if (item.child == row.name) {
            isExist = false;
          }
        });

        return isExist;
      });
    
      if (!keyWord) {
        return data;
      }

      data = data.filter(row =>{
        return String(row.name).toLowerCase().indexOf(keyWord) > -1;
      });

      return data;
    },
    roleItemData() {
      var keyWord = this.roleTogroupKey.item && this.roleTogroupKey.item.toLowerCase();
      if (!this.roleItem) {
        return [];
      }

      var data = [];
      var _this = this;
      data = this.roleItem.filter(row =>{
        var isExist = true;
        _this.itemChildItem.forEach(item =>{
          if (item.child == row.name) {
            isExist = false;
          }
        });

        return isExist;
      });

      if (!keyWord) {
        return data;
      }

      data = data.filter(row =>{
        return String(row.name).toLowerCase().indexOf(keyWord) > -1;
      });

      return data;
    },
    itemChildItemData() {
      var keyWord = this.roleTogroupKey.group && this.roleTogroupKey.group.toLowerCase();
      var role = [];
      var permission = [];
      var data = {'role': role, 'permission': permission};
      var _this = this;

      if (!_this.itemPermission || !_this.roleItem) {
        return [];
      }

      _this.itemPermission.forEach(row =>{
        _this.itemChildItem.forEach(item =>{
          if (item.child == row.name) {
            data.permission.push(row);
          }
        });
      });
      
      _this.roleItem.forEach(row =>{
        _this.itemChildItem.forEach(item =>{
          if (item.child == row.name) {
            data.role.push(row);
          }
        });
      });

      if (!keyWord) {
        return data;
      }

      var newData = {'role': [], 'permission': []};
      newData.permission = data.permission.filter(row =>{
        return String(row.name).toLowerCase().indexOf(keyWord) > -1;
      });

      newData.role = data.role.filter(row =>{
        return String(row.name).toLowerCase().indexOf(keyWord) > -1;
      });
      
      return newData;
    },
  },
  methods: {
    addUserChild(event) {
      var select_users = $(this.$refs["select-user"]).val();
      if (select_users.length == 0) {
        return false;
      }

      var _this = this;
      var $btn = $(event.currentTarget);
      $btn.loading('<i class="fas fa-spinner fa-spin"></i>');
      
      axios.post('{{route("post.group.createChild")}}', {
        group_id: _this.groupInfo.id,
        type: 1,
        childs: select_users
      }).then(function (response) {
        if (response.data.status) {
          select_users.forEach(userId => {
            _this.userChildItem.push({
              group_id: _this.groupInfo.id,
              child: userId,
              type: 1
            });
          });
        } else {
          alert(response.data.msg);
        }

        $btn.loading('reset');
      });
    },
    removeUserChild(event) {
      var select_user_group = $(this.$refs["select-user-group"]).val();
      if (select_user_group.length == 0) {
        return false;
      }

      var _this = this;
      var $btn = $(event.currentTarget);
      $btn.loading('<i class="fas fa-spinner fa-spin"></i>');
      
      axios.delete('{{route("delete.group.destroyChild")}}', { data: {
        group_id: _this.groupInfo.id,
        type: 1,
        childs: select_user_group
      }}).then(function (response) {
        if (response.data.status) {
          for (let i = _this.userChildItem.length-1; i >= 0; i--) {
            if (select_user_group.indexOf(_this.userChildItem[i]['child']) > -1) {
              _this.userChildItem.splice(i, 1);
            }
          }
        } else {
          alert(response.data.msg);
        }

        $btn.loading('reset');
      });
    },
    addItemChild(event) {
      var select_item = $(this.$refs["select-item-child"]).val();
      if (select_item.length == 0) {
        return false;
      }

      var _this = this;
      var $btn = $(event.currentTarget);
      $btn.loading('<i class="fas fa-spinner fa-spin"></i>');
      
      axios.post('{{route("post.group.createChild")}}', {
        group_id: _this.groupInfo.id,
        type: 2,
        childs: select_item,
      }).then(function (response) {
        if (response.data.status) {
          select_item.forEach(row =>{
            _this.itemChildItem.push({group: _this.groupInfo.id, child: row, type: 2});
          });
        } else {
          alert(response.data.status);
        }
        
        $btn.loading('reset');
      });
    },
    removeItemChild(event) {
      var select_item_group = $(this.$refs["select-item-child-group"]).val();
      if (select_item_group.length == 0) {
        return false;
      }

      var _this = this;
      var $btn = $(event.currentTarget);
      $btn.loading('<i class="fas fa-spinner fa-spin"></i>');
      
      axios.delete('{{route("delete.group.destroyChild")}}', { data: {
        group_id: _this.groupInfo.id,
        type: 2,
        childs: select_item_group,
      }}).then(function (response) {
        if (response.data.status) {
          for (let i = _this.itemChildItem.length-1; i >= 0; i--) {
            if (select_item_group.indexOf(_this.itemChildItem[i]['child']) > -1) {
              _this.itemChildItem.splice(i, 1);
            }
          }
        } else {
          alert(response.data.status);
        }

        $btn.loading('reset');
      });
    },
  }
});
</script>
@endsection
