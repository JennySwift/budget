
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

//$rootScope.show = ShowFactory.defaults;

$(window).load(function () {
    $(".main").css('display', 'block');
    $("footer, #navbar").css('display', 'flex');
    $("#page-loading").hide();
    //$rootScope.$emit('getSideBarTotals');

    smoothScroll.init();
});

//$rootScope.deleteUser = function () {
//    if (confirm("Do you really want to delete your account?")) {
//        if (confirm("You are about to delete your account! You will no longer be able to use the budget app. Are you sure this is what you want?")) {
//            $rootScope.showLoading();
//            UsersFactory.deleteAccount(me)
//                .then(function (response) {
//                    $rootScope.$broadcast('provideFeedback', 'Your account has been deleted');
//                    $rootScope.hideLoading();
//                })
//                .catch(function (response) {
//                    $rootScope.responseError(response);
//                });
//        }
//    }
//};



