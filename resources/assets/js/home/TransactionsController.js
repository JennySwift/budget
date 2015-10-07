(function () {

    angular
        .module('budgetApp')
        .controller('TransactionsController', transactions);

    function transactions ($rootScope, $scope, TransactionsFactory, FilterFactory) {

        $scope.transactionsFactory = TransactionsFactory;
        $scope.accounts = accounts_response;

        $rootScope.$on('filterTransactions', function (event, filter) {
            $scope.showLoading();
            FilterFactory.getTransactions(FilterFactory.filter)
                .then(function (response) {
                    $scope.transactions = response.data;
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                })
        });

        $scope.updateReconciliation = function ($transaction) {
            $scope.clearTotalChanges();
            $scope.showLoading();
            TransactionsFactory.updateReconciliation($transaction)
                .then(function (response) {
                    $scope.$emit('getSideBarTotals');
                    $rootScope.$emit('runFilter');
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };

        $scope.updateAllocationStatus = function () {
            $scope.showLoading();
            TransactionsFactory.updateAllocationStatus($scope.allocationPopup)
                .then(function (response) {
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };

        $scope.updateTransactionSetup = function ($transaction) {
            $scope.edit_transaction = $transaction;
            //save the original total so I can calculate
            // the difference if the total changes,
            // so I can remove the correct amount from savings if required.
            $scope.edit_transaction.original_total = $scope.edit_transaction.total;
            $scope.show.edit_transaction = true;
        };

        $scope.updateTransaction = function () {
            $scope.clearTotalChanges();
            $scope.showLoading();
            TransactionsFactory.updateTransaction($scope.edit_transaction)
                .then(function (response) {
                    $scope.$emit('getSideBarTotals');
                    $rootScope.$broadcast('provideFeedback', 'Transaction updated');

                    //Update the transaction in the JS
                    var $index = _.indexOf($scope.transactions, _.findWhere($scope.transactions, {id: $scope.edit_transaction.id}));
                    $scope.transactions[$index] = response.data.data;

                    $scope.show.edit_transaction = false;
                    $scope.totals = response.data;
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };

        /**
         * $scope.edit_transaction.account wasn't updating with ng-model,
         * so I'm doing it manually.
         */
        //$scope.fixEditTransactionAccount = function () {
        //    $account_id = $("#edit-transaction-account").val();
        //
        //    $account_match = _.find($scope.accounts, function ($account) {
        //        return $account.id === $account_id;
        //    });
        //    $account_name = $account_match.name;
        //
        //    $scope.edit_transaction.account.id = $account_id;
        //    $scope.edit_transaction.account.name = $account_name;
        //};

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

        $rootScope.$on('handleAllocationForNewTransaction', function (event, $transaction) {
            FilterFactory.getTransactions(FilterFactory.filter)
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
        });

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

        $scope.deleteTransaction = function ($transaction) {
            if (confirm("Are you sure?")) {
                $scope.clearTotalChanges();
                $scope.showLoading();
                TransactionsFactory.deleteTransaction($transaction, FilterFactory.filter)
                    .then(function (response) {
                        jsDeleteTransaction($transaction);
                        $scope.$emit('getSideBarTotals');
                        //Todo: get filter totals with separate request
                        $rootScope.$broadcast('provideFeedback', 'Transaction deleted');
                        $scope.hideLoading();
                    })
                    .catch(function (response) {
                        $scope.responseError(response);
                    });
            }
        };

        function jsDeleteTransaction ($transaction) {
          var $index = _.indexOf($scope.transactions, _.findWhere($scope.transactions, {id: $transaction.id}));
            $scope.transactions = _.without($scope.transactions, $scope.transactions[$index]);
        }

    }

})();