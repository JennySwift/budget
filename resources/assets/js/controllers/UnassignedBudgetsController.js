(function () {

    angular
        .module('budgetApp')
        .controller('UnassignedBudgetsController', budgets);

    function budgets ($scope, $http, BudgetsFactory, FilterFactory, FeedbackFactory) {
        /**
         * scope properties
         */
        $scope.unassignedBudgetTotals = unassignedBudgetTotals;
    }

})();