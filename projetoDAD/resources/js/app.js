/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require("./bootstrap");

window.Vue = require("vue");
import VueRouter from 'vue-router';
import Vuetify from "vuetify";
import "vuetify/dist/vuetify.css";
import App from './App.vue';
import router from './router.js';
import Vue from 'vue';
import VeeValidate from 'vee-validate';
import store from './vuex.js';
import VueSocketio from 'vue-socket.io';
import Toasted from 'vue-toasted';
import jsPDF from 'jspdf';
import VCharts from 'v-charts'

Vue.use(VCharts)
// install plugin with options
Vue.use(Vuetify);
Vue.use(VueRouter);
Vue.use(VeeValidate);
Vue.use(jsPDF);
//Vue.use(Chat);
Vue.use(new VueSocketio({
    debug: true,
    connection: 'http://oldbutgold.me:8080'
}));

Vue.use(Toasted, {
    theme: "bubble",
    position: "bottom-center",
    duration : 5000,
    type: 'info'
});
window.Event = new Vue;


new Vue({
    el: '#app',
    router,
    store,
    render: h => h(App),
});
