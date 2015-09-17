(function () {

    angular
        .module('budgetApp')
        .controller('TransactionsController', transactions);

    function transactions ($scope, $http, TransactionsFactory, FilterFactory) {

        $scope.transactionsFactory = TransactionsFactory;
        $scope.filterFactory = FilterFactory;
        $scope.accounts = accounts_response;

        $scope.updateReconciliation = function ($transaction) {
            $scope.clearTotalChanges();
            $scope.showLoading();
            TransactionsFactory.updateReconciliation($transaction)
                .then(function (response) {
                    $scope.getSideBarTotals();
                    $scope.filterTransactions();
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
                    $scope.getSideBarTotals();
                    $scope.provideFeedback('Transaction updated');
                    $scope.show.edit_transaction = false;
                    $scope.totals = response.data;
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };

        $scope.fixEditTransactionAccount = function () {
            //$scope.edit_transaction.account wasn't updating with ng-model, so I'm doing it manually.
            $account_id = $("#edit-transaction-account").val();

            $account_match = _.find($scope.accounts, function ($account) {
                return $account.id === $account_id;
            });
            $account_name = $account_match.name;

            $scope.edit_transaction.account.id = $account_id;
            $scope.edit_transaction.account.name = $account_name;
        };

        $scope.massEditTags = function () {
            $scope.showLoading();
            TransactionsFactory.updateMassTags()
                .then(function (response) {
                    multiSearch();
                    $tag_array.length = 0;
                    $tag_location.html($tag_array);
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };

        $scope.massEditDescription = function () {
            $scope.showLoading();
            TransactionsFactory.updateMassDescription()
                .then(function (response) {
                    multiSearch();
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };

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

        $scope.deleteTransaction = function ($transaction) {
            if (confirm("Are you sure?")) {
                $scope.clearTotalChanges();
                $scope.showLoading();
                TransactionsFactory.deleteTransaction($transaction, $scope.filter)
                    .then(function (response) {
                        jsDeleteTransaction($transaction);
                        $scope.getSideBarTotals();
                        //Todo: get filter totals with separate request
                        //FilterFactory.updateDataForControllers(response.data);

                        $scope.provideFeedback('Transaction deleted');
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

        $("#mass-delete-button").on('click', function () {
            if (confirm("You are about to delete " + $(".checked").length + " transactions. Are you sure you want to do this?")) {
                massDelete();
            }
        });

    }

})();