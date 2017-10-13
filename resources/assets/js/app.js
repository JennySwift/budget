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

var App = Vue.component('app', {
    data: function () {
        return {
            show: ShowRepository.defaults,
            transactionPropertiesToShow: ShowRepository.setTransactionDefaults()
        };
    },
    methods: {

        /**
         *
         */
        setHeights: function () {
            var height = $(window).height();
            $('body,html').height(height);
        }
    },
    ready: function () {
        this.setHeights();
        AccountsRepository.getAccounts(this);
        BudgetsRepository.getBudgets(this);
        BudgetsRepository.getUnassignedBudgets(this);
        FavouriteTransactionsRepository.getFavouriteTransactions(this);
        HomePageRepository.setDefaultTab();
        TotalsRepository.getSideBarTotals(this);
        SavedFiltersRepository.getSavedFilters(this);
        FilterRepository.resetFilter();
    }
});


$(window).load(function () {
    $(".main").css('display', 'block');
    $("footer, #navbar").css('display', 'flex');
    $("#page-loading").hide();
    //$rootScope.$emit('getSideBarTotals');

    smoothScroll.init();
});



