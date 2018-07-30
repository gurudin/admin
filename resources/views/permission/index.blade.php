@extends('admin::layouts.config')

@section('title')
{{__('admin::messages.permission.permission')}}
@endsection

@section('content')
<div class="view" id="main-app">
  <div class="col-12">
    <div class="row justify-content-between">
      <div class="col-4 h3">{{__('admin::messages.permission.permission')}}</div>

      <div class="col-4 text-right">
        <a href="{{route('get.permission.create', ['group' => request()->group])}}" class="btn col-4 btn-success">
          {{__('admin::messages.common.create')}}
        </a>
      </div>
    </div>
  </div>

  <div class="col-12">
    <table class="table table-hover">
      <thead class="thead-light">
        <tr>
          <th>{{__('admin::messages.permission.name')}}</th>
          <th>{{__('admin::messages.permission.description')}}</th>
          <th>{{__('admin::messages.common.action')}}</th>
        </tr>
      </thead>

      <tbody>
        <tr>
          <td><input type="text" class="form-control" v-model="searchKey.name" placeholder="{{__('admin::messages.permission.name')}}"></td>
          <td><input type="text" class="form-control" v-model="searchKey.desc" placeholder="{{__('admin::messages.permission.description')}}"></td>
          <td></td>
        </tr>
        <tr v-for="(item,inx) in permissionItemData" v-cloak>
          <td>@{{item.name}}</td>
          <td>@{{item.description}}</td>
          <td>
            <a :href="'{{route('get.permission.view')}}/' + encodeURIComponent(item.name) + '?group={{request()->group}}'" class="btn btn-info text-white btn-sm">
              <i class="fas fa-eye"></i>
            </a>
            <button type="button" class="btn btn-warning text-white btn-sm" @click="editModal(item)">
              <i class="fas fa-edit"></i>
            </button>
            <button type="button" class="btn btn-danger btn-sm" @click="deletePermission($event, inx, item)"><i class="fas fa-trash-alt"></i></button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Edit permission modal -->
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{__('admin::messages.permission.permission')}}: @{{editModel.old.name}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form>
            <div class="form-group">
              <label for="recipient-name" class="col-form-label">{{__('admin::messages.permission.name')}}:</label>
              <input type="text" class="form-control" v-model="editModel.new.name" placeholder="{{__('admin::messages.permission.name')}}">
            </div>
            <div class="form-group">
              <label for="message-text" class="col-form-label">{{__('admin::messages.permission.description')}}:</label>
              <input type="text" class="form-control" v-model="editModel.new.description" placeholder="{{__('admin::messages.permission.description')}}">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('admin::messages.common.cancel')}}</button>
          <button type="button" class="btn btn-success" @click="edit">{{__('admin::messages.common.save')}}</button>
        </div>
      </div>
    </div>
  </div>
  <!-- /Edit permission modal -->
</div>
@endsection

@section('script')
@parent
<script>
new Vue({
  el: '#main-app',
  data() {
    return {
      permissionItem: @json($items),
      searchKey: { name: "", desc: "" },
      editModel: { old: {}, new: {}},
    };
  },
  computed: {
    permissionItemData() {
      var keyName = this.searchKey.name;
      var keyDesc = this.searchKey.desc;
      var data = this.permissionItem.permission;
      if (!data) {
        return [];
      }
    
      data = data.filter(row => {
        if (keyName) {
          var tmpName = String(row.name).indexOf(keyName) > -1;
        } else {
          var tmpName = true;
        }

        if (keyDesc) {
          var tmpDesc = String(row.name).indexOf(keyDesc) > -1;
        } else {
          var tmpDesc = true;
        }

        return tmpName && tmpDesc;
      });

      return data;
    }
  },
  methods: {
    deletePermission(event, inx, item) {
      if (!confirm('{{__('admin::messages.common.are-you-sure-to-delete-this-item')}}')) {
        return false;
      }

      var _this = this;
      var $btn = $(event.currentTarget);
      $btn.loading('<i class="fas fa-spinner fa-spin"></i>');
      axios.delete('{{route("delete.permission.destroy")}}', { data: {
        name: item.name,
        method: item.method
      }}).then(function (response) {
        if (response.data.status) {
          _this.permissionItem.permission.splice(inx, 1);
        } else {
          alert(res.body.msg);
        }
        $btn.loading("reset");
      });
    },
    editModal(item) {
      $("#editModal").modal('show');
      this.editModel.old = item;
      this.editModel.new = $.extend({}, item);
    },
    edit(event) {
      var $btn = $(event.currentTarget);
      $btn.loading('<i class="fas fa-spinner fa-spin"></i>');
      
      var _this = this;

      axios.put('{{route("put.permission.update")}}', this.editModel).then(function (response) {
        if (response.data.status) {
          window.location.reload();
        } else {
          alert(res.body.msg);
          $btn.loading("reset");
        }
      });
    }
  }
});
</script>
@endsection
