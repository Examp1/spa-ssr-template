/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import RequestForm from './components/RequestForm.vue'

Vue.component('request_form',RequestForm)

let app = new Vue({
    el: '#request_app',
    data: function () {
        return {
        }
    },
    methods: {

    }
});
