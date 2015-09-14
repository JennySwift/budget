(function () {

    angular
        .module('budgetApp')
        .controller('FlexBudgetsController', budgets);

    function budgets ($scope, $http, BudgetsFactory, FilterFactory, FeedbackFactory) {
        /**
         * scope properties
         */


    }

})();