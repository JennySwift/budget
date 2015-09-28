(function () {

    angular
        .module('budgetApp')
        .controller('HomeController', home);

    function home ($scope, TransactionsFactory) {

        $scope.transactionsFactory = TransactionsFactory;
        $scope.page = 'home';
        $scope.budgets = budgets;
        $scope.colors = me.preferences.colors;

        $scope.toggleFilter = function () {
            $scope.show.filter = !$scope.show.filter;
        };

    }

})();