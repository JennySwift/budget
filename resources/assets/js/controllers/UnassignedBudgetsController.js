(function () {

    angular
        .module('budgetApp')
        .controller('UnassignedBudgetsController', unassignedBudgets);

    function unassignedBudgets ($scope) {

        $scope.unassignedBudgetTotals = unassignedBudgetTotals;
    }

})();