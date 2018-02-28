/* jshint esversion: 6 */

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example', require('./components/Example.vue'));

import Vue from 'vue';
import App from './App.vue';
import router from './router';
import VeeValidate from 'vee-validate';
import { rutInputDirective } from 'vue-dni';

Vue.component('App', require('./App.vue'));
Vue.use(VeeValidate);
Vue.directive('rut', rutInputDirective);

/* eslint-disable no-new */
window.app = new Vue({
    el: '#app',
    router,
    template: '<App/>',
    components: { App },
});
