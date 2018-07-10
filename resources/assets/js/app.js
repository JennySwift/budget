require('./bootstrap');

window.Vue = require('vue');

import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter);

import store from './repositories/Store'
import helpers from './repositories/Helpers'

window.store = store;
window.helpers = helpers;

require('./config.js');
require('./components');
require('./directives');
require('./method-calls');
import routes from './routes'

window.Event = new Vue();
const bus = new Vue();
Vue.prototype.$bus = bus;

const router = new VueRouter({
    routes
})

const app = new Vue({
    el: '#app',
    router: router,
    mounted: function () {

    },
}).$mount('#app')







