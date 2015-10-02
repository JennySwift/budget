(function () {

    angular
        .module('budgetApp')
        .controller('HomeController', home);

    function home ($rootScope, $scope, TransactionsFactory, FilterFactory) {

        $scope.transactionsFactory = TransactionsFactory;
        $scope.page = 'home';
        $scope.budgets = budgets;
        $scope.colors = me.preferences.colors;


        if (env === 'local') {
            $scope.tab = 'transactions';
        }
        else {
            $scope.tab = 'transactions';
        }

        $scope.toggleFilter = function () {
            $scope.show.filter = !$scope.show.filter;
        };

        //Putting this here so that transactions update
        //after inserting transaction from newTransactionController
        $scope.transactions = transactions;

        $scope.transactionsTab = function () {
            $scope.tab = 'transactions';
            $scope.show.basic_totals = true;
            $scope.show.budget_totals = true;
            $scope.show.filter = false;
            $rootScope.$emit('runFilter');
        };

        $scope.graphsTab = function () {
            $scope.tab = 'graphs';
            $scope.show.basic_totals = false;
            $scope.show.budget_totals = false;
            $scope.show.filter = true;
            $rootScope.$emit('runFilter');
        };

        if ($scope.tab === 'graphs') {
            $scope.graphsTab();
        }

    }

})();