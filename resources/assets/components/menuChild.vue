<template>
<li class="nav-item">
  <a :href="toHref(model.href)"
    class="nav-link"
    :class="{'active': open}"
    @click="openChild">

    <i class="feather" :class="model.icon"></i>
    <span>{{model.text}}</span>
    <span class="float-right">
      <i :class="isFolder ? 'fas fa-angle-' + (open ? 'down' : 'right') : ''"></i>
    </span>
  </a>

  <transition name="fade">
  <ul v-show="open" v-if="isFolder" class="nav flex-column child_menu">
    <menu-child
      v-for="(model, index) in model.children"
      :key="index"
      :model="model"
      :current-uri="currentUri"
      v-on:listen-child="listenToChild"
      :data-group="dataGroup">
    </menu-child>
  </ul>
  </transition>

</li>
</template>

<script>
export default {
  data() {
    return {
      open: false,
      active: false,
    };
  },
  props: {
    model: null,
    dataGroup: null,
    currentUri: String,
  },
  computed: {
    isFolder() {
      return this.model.children && this.model.children.length;
    }
  },
  methods: {
    toHref(href) {
      return href ? href + '?group=' + this.dataGroup : 'javascript:;';
    },
    openChild() {
      if (this.isFolder) {
        this.open = !this.open;
      }
    },
    listenToChild: function (somedata){
      this.openChild();
    }
  },
  created() {
    var uri = this.currentUri[0] == '/' ? this.currentUri : '/' + this.currentUri;
    if (this.model.href == uri) {
      this.active = true;
      this.open = true;

      this.$emit('listen-child');
    }
  }
};
</script>

<style>
.fade-enter-active, .fade-leave-active {
  transition: opacity .1s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
</style>
