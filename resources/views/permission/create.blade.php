@extends('admin::layouts.config')

@section('title')
{{__('admin::messages.permission.create-permission')}}
@endsection

@section('content')
<div class="view" id="main-app">
  <div class="col-12">
    <div class="row justify-content-between">
      <div class="col-4 h3">{{__('admin::messages.permission.create-permission')}}</div>

      <div class="col-4 text-right">
        <a href="{{url()->previous()}}" class="btn col-4 btn-light">
          <i class="fa fa-arrow-left"></i> {{__('admin::messages.common.back')}}
        </a>
      </div>
    </div>
  </div>

  <div class="col-12">
    <form>
      <div class="form-group">
        <label>{{__('admin::messages.permission.name')}}</label>
        <input
          type="text"
          class="form-control"
          maxlength="50"
          v-model="modelData.name"
          :class="{'is-invalid': validate && modelData.name==''}"
          placeholder="{{__('admin::messages.permission.name')}}">
      </div>

      <div class="form-group">
        <label>{{__('admin::messages.permission.description')}}</label>
        <input
          type="text"
          class="form-control"
          maxlength="50"
          v-model="modelData.description"
          :class="{'is-invalid': validate && modelData.description==''}"
          placeholder="{{__('admin::messages.permission.description')}}">
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
      modelData: {
        name: "",
        method: '',
        type: 2,
        description: ""
      },
      validate: false
    };
  },
  methods: {
    save(event) {
      this.validate = true;
      if (this.modelData.name == "" || this.modelData.description == "") {
        return false;
      }

      var _this = this;
      var $btn = $(event.target);
      $btn.loading();
      axios.post('{{route("post.permission.create")}}', this.modelData).then(function (response) {
        if (response.data.status) {
          window.location = '{{url()->previous()}}';
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
