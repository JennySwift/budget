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

function runBlock ($rootScope) {

    $rootScope.show = {
        popups: {}
    };

    $rootScope.me = me;

    $rootScope.totalChanges = {};

    $rootScope.feedback_messages = [];

    if (typeof env !== 'undefined') {
        $rootScope.env = env;
    }

    $rootScope.clearTotalChanges = function () {
        $rootScope.totalChanges = {};
    };

    //$rootScope.showBar = page === 'home' || page === 'budgets';

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

        //$rootScope.feedback_messages.push($message);

        setTimeout(function () {
            $rootScope.feedback_messages = _.without($rootScope.feedback_messages, $new);
            $rootScope.$apply();
        }, 3000);
    };

    $rootScope.deleteUser = function () {
        if (confirm("Do you really want to delete your account?")) {
            if (confirm("You are about to delete your account! You will no longer be able to use the budget app. Are you sure this is what you want?")) {
                $rootScope.showLoading();
                UsersFactory.deleteAccount($rootScope.me)
                    .then(function (response) {
                        //$rootScope. = response.data;
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
