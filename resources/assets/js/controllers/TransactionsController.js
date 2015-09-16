(function () {

    angular
        .module('budgetApp')
        .controller('TransactionsController', transactions);

    function transactions ($scope, $http, TransactionsFactory, FilterFactory) {
        /**
         * Scope properties
         */

        $scope.transactionsFactory = TransactionsFactory;
        $scope.filterFactory = FilterFactory;
        $scope.transactions = filter_response.transactions;
        $scope.accounts = accounts_response;

        /**
         * Watches
         */

        $scope.$watch('filterFactory.filter', function (newValue, oldValue, scope) {
            if (newValue) {
                scope.filter = newValue;
            }
        });

        $scope.$watch('filterFactory.filter_results.transactions', function (newValue, oldValue, scope) {
            if (newValue) {
                scope.transactions = newValue;
            }
        });

        $scope.updateReconciliation = function ($transaction_id, $reconciliation) {
            $scope.clearTotalChanges();
            $scope.showLoading();
            TransactionsFactory.updateReconciliation($transaction_id, $reconciliation, $scope.filter)
                .then(function (response) {
                    FilterFactory.updateDataForControllers(response.data);
                    $scope.updateTotalsAfterResponse(response);
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
            var $date_entry = $("#edit-transaction-date").val();
            $scope.edit_transaction.date.user = $date_entry;
            $scope.edit_transaction.date.sql = Date.parse($date_entry).toString('yyyy-MM-dd');
            $scope.clearTotalChanges();
            $scope.showLoading();
            TransactionsFactory.updateTransaction($scope.edit_transaction, $scope.filter)
                .then(function (response) {
                    FilterFactory.updateDataForControllers(response.data);
                    $scope.updateTotalsAfterResponse(response);
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

        /**
         * For after the response in $scope.updateAllocation, if I want to just update
         * one tag. I switched to updating all tags so I could do the automatic allocation
         * of the other tags to 0% when one is changed to 100%, so I am not using this function anymore.
         * @param $tag_id
         */
        $scope.updateTagAllocation = function ($tag_id) {
            //find the tag in $scope.allocationPopup.budgets
            var $the_tag = _.find($scope.allocationPopup.budgets, function ($tag) {
                return $tag.id === $tag_id;
            });
            //get the index of the tag in $scope.allocationPopup_transaction.tags
            var $index = _.indexOf($scope.allocationPopup.budgets, $the_tag);
            //make the tag equal the ajax response
            $scope.allocationPopup.budgets[$index] = response.data.allocation_info;
        };

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

        $scope.deleteTransaction = function ($transaction) {
            if (confirm("Are you sure?")) {
                $scope.clearTotalChanges();
                $scope.showLoading();
                TransactionsFactory.deleteTransaction($transaction, $scope.filter)
                    .then(function (response) {
                        jsDeleteTransaction($transaction);
                        $scope.getTotals();
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