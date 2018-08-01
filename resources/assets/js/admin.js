
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import vSelect from 'vue-select';

require('./bootstrap');

require('./custom');

window.Vue = require('vue');

Vue.component('v-select', vSelect);
Vue.component('menu-tree', require('../components/menu.vue'));
Vue.component('menu-child', require('../components/menuChild.vue'));
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const menu = new Vue({
  el: '#gurudin-menu-bar'
});
