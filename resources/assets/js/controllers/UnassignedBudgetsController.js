(function () {

    angular
        .module('budgetApp')
        .controller('UnassignedBudgetsController', budgets);

    function budgets ($scope, $http, BudgetsFactory, FilterFactory, FeedbackFactory) {
        /**
         * scope properties
         */
        $scope.unassignedBudgetTotals = unassignedBudgetTotals;
        $scope.feedbackFactory = FeedbackFactory;

        $scope.show.basic_totals = true;
        $scope.show.budget_totals = true;
        $scope.newBudget = {
            type: 'fixed'
        };

    }

})();