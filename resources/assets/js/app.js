// require('./config');
//These lines from the Laravel install
// require('./bootstrap');
// window.Vue = require('vue');

import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter);

global.$ = require('jquery');
global.jQuery = require('jquery');

// import jQuery from 'jquery'
// global._ = require('underscore');
global._ = require('lodash');
import store from './budget/src/repositories/Store'
window.store = store;

window.Event = new Vue();

require('./components.js');

import routes from './routes'

const router = new VueRouter({
    routes // short for `routes: routes`
})

const bus = new Vue()
Vue.prototype.$bus = bus

const app = new Vue({
    router
}).$mount('#app')

store.getUser();
store.getAccounts();
store.setHeights();

var App = Vue.component('app', {
    data: function () {
        return {

        };
    },
    methods: {

    },
    ready: function () {
        store.getBudgets();
        store.getUnassignedBudgets();
        store.getFavouriteTransactions();
        store.setDefaultTab();
        store.getSideBarTotals();
        store.getSavedFilters();
        store.resetFilter();
        store.setDefaultTransactionPropertiesToShow()
    }
});

$(window).on('load', function () {
    $(".main").css('display', 'block');
    $("footer, #navbar").css('display', 'flex');
    $("#page-loading").hide();
    //Uncomment after refactor
    // smoothScroll.init();
});



