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

        /**
         * Although related to a new transaction, this is here,
         * not in NewTransactionController,
         * because it uses $scope.transactions.
         * @param $transaction
         */
        $scope.handleAllocationForNewTransaction = function ($transaction) {
            FilterFactory.getTransactions($scope.filter)
                .then(function (response) {
                    $scope.hideLoading();
                    $scope.transactions = response.data;
                    var $index = _.indexOf($scope.transactions, _.findWhere($scope.transactions, {id: $transaction.id}));
                    if ($index !== -1) {
                        //The transaction that was just entered is in the filtered transactions
                        $scope.showAllocationPopup($scope.transactions[$index]);
                        //$scope.transactions[$index] = $scope.allocationPopup;
                    }
                    else {
                        $scope.showAllocationPopup($transaction);
                    }
                })
                .catch(function (response) {
                    $scope.responseError(response);
                })
        };

        /**
         * This is here because it is called by $scope.handleAllocationForNewTransaction,
         * which is in this file
         * @param $transaction
         */
        $scope.showAllocationPopup = function ($transaction) {
            $scope.show.allocationPopup = true;
            $scope.allocationPopup = $transaction;

            $scope.showLoading();
            TransactionsFactory.getAllocationTotals($transaction.id)
                .then(function (response) {
                    $scope.allocationPopup.totals = response.data;
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };


    }

})();