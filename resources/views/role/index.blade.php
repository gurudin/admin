@extends('admin::layouts.config')

@section('title')
{{__('admin::messages.role.role')}}
@endsection

@section('content')
<div class="view" id="main-app">
  <div class="col-12">
    <div class="row justify-content-between">
      <div class="col-4 h3">{{__('admin::messages.role.role')}}</div>

      <div class="col-4 text-right">
        <button class="btn col-4 btn-success" @click="showModal('create')">
          {{__('admin::messages.common.create')}}
        </button>
      </div>
    </div>
  </div>

  <div class="col-12" v-cloak>
    <table class="table table-hover">
      <thead class="thead-light">
        <tr>
          <th class="w-25">{{__('admin::messages.role.name')}}</th>
          <th class="w-50">{{__('admin::messages.role.description')}}</th>
          <th class="w-25">{{__('admin::messages.common.action')}}</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="(item,inx) in roleItem">
          <td>@{{item.name}}</td>
          <td>@{{item.description}}</td>
          <td>
            <a :href="'{{route('get.role.view')}}/' + encodeURIComponent(item.name) + '?group={{request()->group}}'" class="btn btn-info btn-sm">
              <i class="fas fa-eye"></i>
            </a>
            <button type="button" class="btn btn-warning text-white btn-sm" @click="showModal('update', item)">
              <i class="fas fa-edit"></i>
            </button>
            <button type="button" class="btn btn-danger btn-sm" @click="deleteRole($event, inx, item)"><i class="fas fa-trash-alt"></i></button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Save role modal -->
  <div class="modal" id="saveModal" style="background: #0000004a;">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{__('admin::messages.role.role')}}@{{modalTitle=='update' ? ': ' + roleModel.old.name : ''}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="close('saveModal')">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form>
            <div class="form-group">
              <label for="recipient-name" class="col-form-label">{{__('admin::messages.role.name')}}:</label>
              <input
                type="text"
                class="form-control"
                :class="{'is-invalid':validate && !roleModel.new.name}"
                v-model.trim="roleModel.new.name"
                placeholder="{{__('admin::messages.role.name')}}">
            </div>
            <div class="form-group">
              <label for="message-text" class="col-form-label">{{__('admin::messages.role.description')}}:</label>
              <input
                type="text"
                class="form-control"
                :class="{'is-invalid':validate && !roleModel.new.description}"
                v-model.trim="roleModel.new.description"
                placeholder="{{__('admin::messages.role.description')}}">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="close('saveModal')">{{__('admin::messages.common.cancel')}}</button>
          <button type="button" class="btn btn-success" @click="save">{{__('admin::messages.common.save')}}</button>
        </div>
      </div>
    </div>
  </div>
  <!-- /Save role modal -->

</div>
@endsection

@section('script')
@parent
<script>
new Vue({
  el: '#main-app',
  data() {
    return {
      roleItem: @json($roles),
      modalTitle: 'create',
      roleModel: {old: {}, new: {}},
      validate: false,
    };
  },
  methods: {
    close(id) {
      $('#' + id).fadeOut();
    },
    showModal(method, item={}) {
      this.modalTitle = method;
      if (method == 'create') {
        this.roleModel.old = {};
        this.roleModel.new = {};
      } else {
        this.roleModel.old = item;
        this.roleModel.new = $.extend({}, item);
      }
      
      $('#saveModal').fadeIn();
    },
    deleteRole(event, inx, item) {
      if (!confirm('{{__('admin::messages.common.are-you-sure-to-delete-this-item')}}')) {
        return false;
      }

      var _this = this;
      var $btn = $(event.currentTarget);
      $btn.loading('<i class="fas fa-spinner fa-spin"></i>');
      axios.delete('{{route("delete.role.destroy")}}', { data: {
        name: item.name,
        method: item.method
      }}).then(function (response) {
        if (response.data.status) {
          _this.roleItem.splice(inx, 1);
        } else {
          alert(response.data.msg);
        }
        $btn.loading('reset');
      });
    },
    save(event) {
      this.validate = true;
      if (!this.roleModel.new.name
        || !this.roleModel.new.description
      ) {
        return false;
      }

      this.roleModel.new.type = 1;
      this.roleModel.new.method = '';

      var $btn = $(event.currentTarget);
      var _this = this;
      $btn.loading('<i class="fas fa-spinner fa-spin"></i>');
      if (JSON.stringify(this.roleModel.old) == "{}") {
        // Create
        axios.post('{{route("post.role.create")}}', this.roleModel.new).then(function (response) {
          if (response.data.status) {
            window.location.reload();
          } else {
            alert(response.data.msg);
            $btn.loading('reset');
          }
        });
      } else {
        // Update
        axios.put('{{route("put.role.update")}}', this.roleModel).then(function (response) {
          if (response.data.status) {
            window.location.reload();
          } else {
            alert(response.data.msg);
            $btn.loading('reset');
          }
        });
      }
    }
  }
});
</script>
@endsection
