/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('babel-polyfill');

require('./bootstrap');

window.Vue = require('vue');
window.Promise = require('promise');

import Buefy from 'buefy';
import 'bulma/css/bulma.css';
import 'buefy/dist/buefy.css';
import "@mdi/font/css/materialdesignicons.css";
import "@fortawesome/fontawesome-free/css/all.css";
import sanitizeHTML from 'sanitize-html';
import moment from 'vue-moment';

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('index', require('./components/Index.vue').default);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.use(Buefy);
Vue.use(moment);
Vue.prototype.$sanitize = sanitizeHTML;

const app = new Vue({
    el: '#app',
});
