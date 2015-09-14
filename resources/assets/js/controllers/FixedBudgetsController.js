(function () {

    angular
        .module('budgetApp')
        .controller('FixedBudgetsController', budgets);

    function budgets ($scope, $http, BudgetsFactory, FilterFactory, FeedbackFactory) {
        /**
         * scope properties
         */


    }

})();