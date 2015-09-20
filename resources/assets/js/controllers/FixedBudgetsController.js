(function () {

    angular
        .module('budgetApp')
        .controller('FixedBudgetsController', fixedBudgets);

    function fixedBudgets ($scope) {

        $scope.fixedBudgetTotals = fixedBudgetTotals;

    }

})();