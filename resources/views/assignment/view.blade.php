@extends('admin::layouts.config')

@section('title')
{{__('admin::messages.assignment.assignment')}}
@endsection

@section('content')
<div class="view" id="main-app" v-cloak>
  <div class="col-12">
    <div class="row justify-content-between">
      <div class="col-8 h3">{{__('admin::messages.assignment.assignment')}}: @{{ userDetail.name }}</div>

      <div class="col-4 text-right">
        <a href="{{url()->previous()}}" class="btn col-4 btn-light">
          <i class="fa fa-arrow-left"></i> {{__('admin::messages.common.back')}}
        </a>
      </div>
    </div>
  </div>

  <div class="row col-12">
    <div class="input-group mb-3 col-12">
      <div class="input-group-prepend">
        <label class="input-group-text">{{__('admin::messages.assignment.select-group')}}</label>
      </div>
      <select class="custom-select" v-model="defaultGroup">
        <option v-for="item in userDetail.group" :value="item.group_id">@{{item.name}}</option>
      </select>
    </div>

    <div class="col-12"><br></div>

    <div class="col">
      <div class="form-group">
        <input type="text" class="form-control" v-model="searchKey.distributor" placeholder="{{__('admin::messages.assignment.search-for-permission')}}">
        <select multiple class="form-control" size="20" ref="select-distributor">
          <optgroup label="{{__('admin::messages.assignment.permission')}}">
            <option v-for="item in distributorData">@{{item}}</option>
          </optgroup>
        </select>
      </div>
    </div>

    <div class="col-2 text-center" style="margin-top: 10%;">
      <div class="btn-group-vertical">
        <button type="button" class="btn btn-success btn-lg" @click="addAssignment"><i class="fas fa-chevron-right"></i></button>
        <button type="button" class="btn btn-danger btn-lg" @click="removeAssignment"><i class="fas fa-chevron-left"></i></button>
      </div>
    </div>

    <div class="col">
      <div class="form-group">
        <input type="text" class="form-control" v-model="searchKey.assignee" placeholder="{{__('admin::messages.assignment.search-for-permission')}}">
        <select multiple class="form-control" size="20" ref="select-assignee">
          <optgroup label="{{__('admin::messages.assignment.permission')}}">
            <option v-for="item in permissionData">@{{item}}</option>
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
      userId: '{{$user_detail["id"]}}',
      groupId: '{{request()->group}}',
      userDetail: @json($user_detail),
      distributorDetail: @json($distributor),
      searchKey: {distributor: '', assignee: ''},
      defaultGroup: '{{$user_detail["group"][0]["group_id"]}}',
    };
  },
  computed: {
    distributorData() {
      var keyWord = this.searchKey.distributor && this.searchKey.distributor.toLowerCase();
      var _this = this;
      var data = [];
      var permission = [];

      if (JSON.stringify(this.distributorDetail) == '{}') {
        return [];
      }

      this.distributorDetail.forEach(row => {
        if (row.id == _this.defaultGroup) {
          data = row;
        }
      });
    
      permission = data.child.filter(row =>{
        var isChk = true;
        for (let i = _this.permissionData.length - 1; i >= 0; i--) {
          if (_this.permissionData[i] == row){
            isChk = false;
          }
        }
        
        return isChk && String(row).toLowerCase().indexOf(keyWord) > -1;
      });
      
      return permission;
    },
    permissionData() {
      var keyWord = this.searchKey.assignee && this.searchKey.assignee.toLowerCase();
      var _this = this;
      var data = [];
      var permission = [];

      if (JSON.stringify(this.userDetail) == '{}') {
        return [];
      }

      this.userDetail.group.forEach(row => {
        if (row.group_id == _this.defaultGroup) {
          data = row;
        }
      });

      permission = data.permission.filter(row =>{
        return String(row).toLowerCase().indexOf(keyWord) > -1;
      });

      return permission;
    },
  },
  methods: {
    addAssignment(event) {
      var selectAssign = $(this.$refs['select-distributor']).val();
      var _this = this;

      if (selectAssign.length == 0) {
        return false;
      }

      var $btn = $(event.currentTarget);
      $btn.loading('<i class="fas fa-spinner fa-spin"></i>');
      
      axios.post('{{route("post.assignment.create")}}', {
        user_id: this.userId,
        group_id: this.defaultGroup,
        items: selectAssign
      }).then(function (response) {
        if (response.data.status) {
          _this.userDetail.group.forEach((row, inx) =>{
            if (row.group_id == _this.defaultGroup) {
              selectAssign.forEach(item =>{
                row.permission.push(item);
              });
            }
          });
        } else {
          alert(response.data.msg);
        }

        $btn.loading('reset');
      });
    },
    removeAssignment(event) {
      var removeAssign = $(this.$refs['select-assignee']).val();
      var _this = this;

      if (removeAssign.length == 0) {
        return false;
      }

      var $btn = $(event.currentTarget);
      $btn.loading('<i class="fas fa-spinner fa-spin"></i>');
      
      axios.delete('{{route("delete.assignment.destroy")}}', { data: {
        user_id: this.userId,
        group_id: this.defaultGroup,
        items: removeAssign
      }}).then(function (response) {
        if (response.data.status) {
          _this.userDetail.group.forEach((row, inx) =>{
            if (row.group_id == _this.defaultGroup) {
              for (let i = row.permission.length; i >= 0; i--) {
                if (removeAssign.indexOf(row.permission[i]) > -1) {
                  row.permission.splice(i, 1);
                }
              }
            }
          });
        } else {
          alert(response.data.msg);
        }

        $btn.loading('reset');
      });
    }
  }
});
</script>
@endsection
