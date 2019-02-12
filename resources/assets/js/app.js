
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

const MuseUI = require('muse-ui');
Vue.use(MuseUI);

import 'muse-ui/dist/muse-ui.css'

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/Example.vue'));

Vue.component('admin-login', require('./views/admin/Login.vue'));

Vue.component('admin-home', require('./views/admin/Home.vue'));

Vue.component('admin-booking', require('./views/admin/Booking.vue'));

Vue.component('admin-export', require('./views/admin/Export.vue'));


Vue.component('admin-staff', require('./views/admin/Staff.vue'));

const app = new Vue({
    el: '#app'
});
