
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('theatre-select', require('./components/TheatreSelect.vue'));
Vue.component('language-select', require('./components/LanguageSelect.vue'));
Vue.component('screening', require('./components/Screening.vue'));
Vue.component('movies', require('./components/Movies.vue'));
Vue.component('movie', require('./components/Movie.vue'));
Vue.component('version', require('./components/Version.vue'));
Vue.component('theatre', require('./components/Theatre.vue'));

const app = new Vue({
    el: '#app'
});
