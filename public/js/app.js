var app = angular.module('budgetApp', [
    'checklist-model',
    'ngAnimate'
]);

app.config(function ($interpolateProvider) {
    //$routeProvider
    //    .when('home', {controller: 'HomeController'})
    //    .when('accounts', {controller: 'AccountsController'})
    //    .when('budgets', {controller: 'BudgetsController'})
    //    .when('help', {controller: 'HelpController'})
    //    .when('preferences', {controller: 'PreferencesController'});

    // register http interceptor
    //$httpProvider.interceptors.push('ErrorHandler');

    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});

app.run(runBlock);

function runBlock ($rootScope, $sce, UsersFactory, TotalsFactory, ShowFactory, ErrorsFactory) {

    $rootScope.show = ShowFactory.defaults;

    $rootScope.feedback_messages = [];

    $rootScope.totalChanges = {};

    $rootScope.feedback_messages = [];

    $rootScope.clearTotalChanges = function () {
        $rootScope.totalChanges = {};
    };

    if (typeof env !== 'undefined') {
        $rootScope.env = env;
    }

    /**
     * Todo: custom angular filter
     * @param $date
     * @returns {*}
     */
    $rootScope.formatDate = function ($date) {
        if ($date) {
            if (!Date.parse($date)) {
                $rootScope.provideFeedback('Date is invalid', 'error');
                return false;
            }
            else {
                return Date.parse($date).toString('yyyy-MM-dd');
            }
        }
        return false;
    };

    $rootScope.clearTotalChanges = function () {
        $rootScope.totalChanges = {};
    };

    $rootScope.responseError = function (response) {
        $rootScope.provideFeedback(ErrorsFactory.responseError(response), 'error');
        $rootScope.hideLoading();
    };

    $rootScope.getSideBarTotals = function () {
        $rootScope.totalsLoading = true;
        TotalsFactory.getSideBarTotals()
            .then(function (response) {
                $rootScope.sideBarTotals = response.data.data;
                $rootScope.totalsLoading = false;
            })
            .catch(function (response) {
                $rootScope.responseError(response);
            });
    };

    if (typeof page !== 'undefined' && (page === 'home' || page === 'fixedBudgets' || page === 'flexBudgets' || page === 'unassignedBudgets')) {
        $rootScope.getSideBarTotals();
    }

    $rootScope.closePopup = function ($event, $popup) {
        var $target = $event.target;
        if ($target.className === 'popup-outer') {
            $rootScope.show.popups[$popup] = false;
        }
    };

    $(window).load(function () {
        $(".main").css('display', 'block');
        $("footer, #navbar").css('display', 'flex');
        $("#page-loading").hide();
    });

    $rootScope.showLoading = function () {
        $rootScope.loading = true;
    };

    $rootScope.hideLoading = function () {
        $rootScope.loading = false;
    };

    $rootScope.provideFeedback = function ($message, $type) {
        var $new = {
            message: $sce.trustAsHtml($message),
            type: $type
        };

        $rootScope.feedback_messages.push($new);

        setTimeout(function () {
            $rootScope.feedback_messages = _.without($rootScope.feedback_messages, $new);
            $rootScope.$apply();
        }, 3000);
    };

    $rootScope.deleteUser = function () {
        if (confirm("Do you really want to delete your account?")) {
            if (confirm("You are about to delete your account! You will no longer be able to use the budget app. Are you sure this is what you want?")) {
                $rootScope.showLoading();
                UsersFactory.deleteAccount(me)
                    .then(function (response) {
                        $rootScope.provideFeedback('Your account has been deleted');
                        $rootScope.hideLoading();
                    })
                    .catch(function (response) {
                        $rootScope.responseError(response);
                    });
            }
        }
    };

}

//# sourceMappingURL=app.js.map