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

        $scope.filter = FilterFactory.filter;
        $scope.filterTotals = filterBasicTotals;

        $scope.getFilterBasicTotals = function () {
            FilterFactory.getBasicTotals($scope.filter)
                .then(function (response) {
                    $scope.filterTotals = response.data;
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                })
        };

        $scope.getGraphTotals = function () {
            FilterFactory.getGraphTotals($scope.filter)
                .then(function (response) {
                    $scope.graphTotals = response.data;
                    calculateGraphFigures();
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                })
        };

        /**
         * This is here because it calls $scope.runFilter,
         * and that method is in this file
         */
        $scope.resetFilter = function () {
            $scope.filter = FilterFactory.resetFilter();
            $rootScope.$emit('runFilter');
        };

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
         * This is here because it is called by $scope.runFilter,
         * which is in this file.
         */
        function calculateGraphFigures () {
            $scope.graphFigures = FilterFactory.calculateGraphFigures($scope.graphTotals);
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

        /**
         * This should be in transactions controller but it wasn't firing for some reason
         * @param $keycode
         * @param $type
         * @param $value
         * @param $budget_id
         */
        $scope.updateAllocation = function ($keycode, $type, $value, $budget_id) {
            if ($keycode === 13) {
                $scope.showLoading();
                TransactionsFactory.updateAllocation($type, $value, $scope.allocationPopup.id, $budget_id)
                    .then(function (response) {
                        $scope.allocationPopup.budgets = response.data.budgets;
                        $scope.allocationPopup.totals = response.data.totals;
                        $scope.hideLoading();
                    })
                    .catch(function (response) {
                        $scope.responseError(response);
                    });
            }
        };


        /**
         * This should be in transactions controller but it wasn't firing for some reason
         */
        $scope.updateAllocationStatus = function () {
            $scope.showLoading();
            TransactionsFactory.updateAllocationStatus($scope.allocationPopup.id, $scope.allocationPopup.allocated)
                .then(function (response) {
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };

    }

})();