import TotalsRepository from "./repositories/TotalsRepository";

require('./bootstrap');

window.Vue = require('vue');

import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter);

import store from './repositories/Store'
import helpers from './repositories/helpers/Helpers'

window.store = store;
window.helpers = helpers;

require('./config.js');
require('./components');
require('./directives');
import routes from './routes'
import FilterRepository from "./repositories/FilterRepository";

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
        store.getUser();
        store.getEnvironment();
        store.getAccounts();
        store.setHeights();
        store.getBudgets();
        store.getUnassignedBudgets();
        store.getFavouriteTransactions();
        store.setDefaultTab();
        store.getSideBarTotals();
        store.getSavedFilters();
        store.setDefaultTransactionPropertiesToShow();
        setTimeout(function () {
            FilterRepository.runFilter();
        }, 100);
    },
}).$mount('#app')


$(window).on('load', function () {
    // $(".main").css('display', 'block');
    // $("footer, #navbar").css('display', 'flex');
    // $("#page-loading").hide();
    //Uncomment after refactor
    // smoothScroll.init();
});







