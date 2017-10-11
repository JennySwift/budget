
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

Vue.component('example', require('./components/Example.vue'));

const app = new Vue({
    el: '#app'
});
















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
    }
});

var router = new VueRouter({
    hashbang: false
});

router.map({
    '/': {
        component: TransactionsPage,
        //subRoutes: {
        //    //default for if no id is specified
        //    '/': {
        //        component: Item
        //    },
        //    '/:id': {
        //        component: Item
        //    }
        //}
    },
    '/help': {
        component: HelpPage
    },
    '/feedback': {
        component: FeedbackPage
    },
    '/accounts': {
        component: AccountsPage
    },
    '/preferences': {
        component: PreferencesPage
    },
    '/fixed-budgets': {
        component: FixedBudgetsPage
    },
    '/flex-budgets': {
        component: FlexBudgetsPage
    },
    '/unassigned-budgets': {
        component: UnassignedBudgetsPage
    },
    '/graphs': {
        component: GraphsPage
    },
    '/favourite-transactions': {
        component: FavouriteTransactionsPage
    }
});

router.start(App, 'body');

$(window).load(function () {
    $(".main").css('display', 'block');
    $("footer, #navbar").css('display', 'flex');
    $("#page-loading").hide();
    //$rootScope.$emit('getSideBarTotals');

    smoothScroll.init();
});



