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

function runBlock ($rootScope, UsersFactory, ShowFactory, ErrorsFactory) {

    $rootScope.show = ShowFactory.defaults;

    $rootScope.responseError = function (response) {
        $rootScope.$broadcast('provideFeedback', ErrorsFactory.responseError(response), 'error');
        $rootScope.hideLoading();
    };

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
        $rootScope.$emit('getSideBarTotals');
    });

    $rootScope.showLoading = function () {
        $rootScope.loading = true;
    };

    $rootScope.hideLoading = function () {
        $rootScope.loading = false;
    };

    $rootScope.deleteUser = function () {
        if (confirm("Do you really want to delete your account?")) {
            if (confirm("You are about to delete your account! You will no longer be able to use the budget app. Are you sure this is what you want?")) {
                $rootScope.showLoading();
                UsersFactory.deleteAccount(me)
                    .then(function (response) {
                        $rootScope.$broadcast('provideFeedback', 'Your account has been deleted');
                        $rootScope.hideLoading();
                    })
                    .catch(function (response) {
                        $rootScope.responseError(response);
                    });
            }
        }
    };

}
