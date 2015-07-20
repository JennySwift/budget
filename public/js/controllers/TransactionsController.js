(function () {

    angular
        .module('budgetApp')
        .controller('TransactionsController', transactions);

    function transactions ($scope, $http, TransactionsFactory, FilterFactory, BudgetsFactory) {
        $scope.transactionsFactory = TransactionsFactory;
        $scope.filterFactory = FilterFactory;

        $scope.transactions = filter_response.transactions;

        $scope.tags = tags_response;

        $scope.accounts = accounts_response;

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
            TransactionsFactory.updateReconciliation($transaction_id, $reconciliation)
                .then(function (response) {
                    $scope.multiSearch();
                    $scope.totals = response.data;
                })
                .catch(function (response) {
                    $scope.provideFeedback('There was an error');
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
            TransactionsFactory.updateTransaction($scope.edit_transaction, $scope.filter)
                .then(function (response) {
                    FilterFactory.updateDataForControllers(response.data);
                    $scope.provideFeedback('Transaction updated');

                    $scope.show.edit_transaction = false;

                    $scope.totals = response.data;
                })
                .catch(function (response) {
                    $scope.provideFeedback('There was an error');
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
            TransactionsFactory.updateMassTags()
                .then(function (response) {
                    multiSearch();
                    $tag_array.length = 0;
                    $tag_location.html($tag_array);
                })
                .catch(function (response) {
                    $scope.provideFeedback('There was an error');
                });
        };

        $scope.massEditDescription = function () {
            TransactionsFactory.updateMassDescription()
                .then(function (response) {
                    multiSearch();
                })
                .catch(function (response) {
                    $scope.provideFeedback('There was an error');
                });
        };

        $scope.updateAllocation = function ($keycode, $type, $value, $tag_id) {
            if ($keycode === 13) {
                BudgetsFactory.updateAllocation($type, $value, $scope.allocation_popup.id, $tag_id)
                    .then(function (response) {
                        //find the tag in $scope.allocation_popup.tags
                        var $the_tag = _.find($scope.allocation_popup.tags, function ($tag) {
                            return $tag.id === $tag_id;
                        });
                        //get the index of the tag in $scope.allocation_popup_transaction.tags
                        var $index = _.indexOf($scope.allocation_popup.tags, $the_tag);
                        //make the tag equal the ajax response
                        $scope.allocation_popup.tags[$index] = response.data.allocation_info;
                        $scope.allocation_popup.allocation_totals = response.data.allocation_totals;
                    })
                    .catch(function (response) {
                        $scope.provideFeedback('There was an error');
                    });
            }
        };

        $scope.updateAllocationStatus = function () {
            BudgetsFactory.updateAllocationStatus($scope.allocation_popup.id, $scope.allocation_popup.allocated)
                .then(function (response) {
                    console.log("something");
                })
                .catch(function (response) {
                    $scope.provideFeedback('There was an error');
                });
        };

        $scope.deleteTransaction = function ($transaction) {
            if (confirm("Are you sure?")) {
                TransactionsFactory.deleteTransaction($transaction, $scope.filter)
                    .then(function (response) {
                        $scope.totals = response.data.totals;
                        //$scope.calculateAmountToTakeFromSavings($transaction);

                        FilterFactory.updateDataForControllers(response.data);

                        $scope.provideFeedback('Transaction deleted');
                    })
                    .catch(function (response) {
                        $scope.provideFeedback('There was an error');
                    });
            }
        };

        //$scope.calculateAmountToTakeFromSavings = function ($transaction) {
        //    //reverse the automatic insertion into savings if it is an income expense
        //    if ($transaction.type === 'income') {
        //        //This value will change. Just for developing purposes.
        //        var $percent = 10;
        //        var $amount_to_subtract = $transaction.total / 100 * $percent;
        //        $scope.reverseAutomaticInsertIntoSavings($amount_to_subtract);
        //    }
        //};

        $("#mass-delete-button").on('click', function () {
            if (confirm("You are about to delete " + $(".checked").length + " transactions. Are you sure you want to do this?")) {
                massDelete();
            }
        });

    }

})();