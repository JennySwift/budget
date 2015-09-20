(function () {

    angular
        .module('budgetApp')
        .controller('UnassignedBudgetsController', budgets);

    function budgets ($scope) {

        $scope.unassignedBudgetTotals = unassignedBudgetTotals;
    }

})();