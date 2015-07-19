(function () {

    angular
        .module('budgetApp')
        .controller('NewTransactionController', newTransaction);

    function newTransaction ($scope, $http, autocomplete) {
        /**
         * scope properties
         */

        $scope.new_transaction = {
            total: '10',
            type: 'income',
            account: 1,
            description: 'test clear fields',
            merchant: 'merchant',
            date: {
                entered: 'today'
            },
            reconciled: false,
            multiple_budgets: false,
            tags: [
                // {
                // 	id: '16',
                // 	name: 'test',
                // 	fixed_budget: '10.00',
                // 	flex_budget: null
                // },
                // {
                // 	id: '17',
                // 	name: 'testtwo',
                // 	fixed_budget: null,
                // 	flex_budget: '5'
                // }
            ]
        };

        $scope.accounts = accounts_response;
        if ($scope.accounts[0]) {
            //this if check is to get rid of the error for a new user who does not yet have any accounts.
            $scope.new_transaction.account = $scope.accounts[0].id;
            $scope.new_transaction.from_account = $scope.accounts[0].id;
            $scope.new_transaction.to_account = $scope.accounts[0].id;
        }

        $scope.tags = tags_response;

        $scope.types = ["income", "expense", "transfer"];

        $scope.clearNewTransactionFields = function () {
            $scope.new_transaction.tags = [];

            if (me.settings.clear_fields) {
                $scope.new_transaction.total = '';
                $scope.new_transaction.description = '';
                $scope.new_transaction.merchant = '';
                $scope.new_transaction.reconciled = false;
                $scope.new_transaction.multiple_budgets = false;
            }
        };

        $scope.errorCheck = function () {
            $scope.messages = {};

            var $date_entry = $("#date").val();
            $scope.new_transaction.date.sql = Date.parse($date_entry).toString('yyyy-MM-dd');

            if ($scope.new_transaction.date.sql === null) {
                $scope.messages.invalid_date = true;
                return false;
            }
            else if ($scope.new_transaction.total === "") {
                $scope.message.total_required = true;
                return false;
            }
            else if (!$.isNumeric($scope.new_transaction.total)) {
                $scope.messages.total_not_number = true;
                return false;
            }
            else if ($scope.new_transaction.type === 'transfer' && $scope.new_transaction.from_account === "from") {
                $scope.messages.from_account_required = true;
                return false;
            }
            else if ($scope.new_transaction.type === 'transfer' && $scope.new_transaction.to_account === "to") {
                $scope.messages.to_account_required = true;
                return false;
            }
            return true;
        };

        $scope.insertTransaction = function ($keycode) {
            if ($keycode !== 13 || !$scope.errorCheck()) {
                return;
            }

            TransactionsFactory.insertTransaction($scope.new_transaction)
                .then(function (response) {
                    $scope.provideFeedback('Transaction added');
                    $scope.checkNewTransactionForMultipleBudgets(response);
                    $scope.clearNewTransactionFields();
                    $scope.new_transaction.dropdown = false;


                    $scope.totals = response.data.totals;
                    $scope.multiSearch(false, true);
                })
                .catch(function (response) {
                    $scope.provideFeedback('There was an error');
                });
        };

        /**
         * See if the transaction that was just entered has multiple budgets.
         * The allocation popup is shown from $scope.multiSearch().
         */
        $scope.checkNewTransactionForMultipleBudgets = function (response) {
            var $transaction = response.data.transaction;
            var $multiple_budgets = response.data.multiple_budgets;

            if ($multiple_budgets) {
                $scope.new_transaction.multiple_budgets = true;
                $scope.allocation_popup_transaction = $transaction;
            }
            else {
                $scope.new_transaction.multiple_budgets = false;
            }
        };


        /**
         * For the transaction autocomplete
         * @param $keycode
         * @param $typing
         * @param $field
         */
        $scope.filterTransactions = function ($keycode, $typing, $field) {
            if ($keycode !== 38 && $keycode !== 40 && $keycode !== 13) {
                //not up arrow, down arrow or enter
                //show the dropdown
                $scope.show.autocomplete[$field] = true;
                // so filter transactions
                autocomplete.removeSelected($scope.transactions);
                //fetch the transactions that match $typing to display in the autocomplete dropdown
                autocomplete.filterTransactions($typing, $field)
                    .then(function (response) {
                        $scope.autocomplete.transactions = response.data;
                        $scope.autocomplete.transactions = autocomplete.transferTransactions($scope.autocomplete.transactions);
                        $scope.autocomplete.transactions = autocomplete.removeDuplicates($scope.autocomplete.transactions);
                    })
                    .catch(function (response) {
                        $scope.provideFeedback('There was an error');
                    });
            }
            else if ($keycode === 38) {
                //up arrow
                $scope.selected = autocomplete.upArrow($scope.autocomplete.transactions);
            }
            else if ($keycode === 40) {
                //down arrow
                $scope.selected = autocomplete.downArrow($scope.autocomplete.transactions);
            }
            else if ($keycode === 13) {
                var $selected = _.find($scope.autocomplete.transactions, function ($item) {
                    return $item.selected === true;
                });
                if ($selected) {
                    //fill in the fields
                    $scope.autocompleteTransaction();
                }
                else {
                    $scope.insertTransaction(13);
                }
            }
        };

        $scope.autocompleteTransaction = function ($selected) {
            //fills in the fields
            $selected = $selected || _.find($scope.autocomplete.transactions, function ($transaction) {
                return $transaction.selected === true;
            });
            $scope.new_transaction.description = $selected.description;
            $scope.new_transaction.merchant = $selected.merchant;
            $scope.new_transaction.total = $selected.total;
            $scope.new_transaction.type = $selected.type;
            $scope.new_transaction.account = $selected.account.id;

            if ($selected.from_account && $selected.to_account) {
                $scope.new_transaction.from_account = $selected.from_account.id;
                $scope.new_transaction.to_account = $selected.to_account.id;
            }

            $scope.new_transaction.tags = $selected.tags;

            $scope.show.autocomplete.description = false;
            $scope.show.autocomplete.merchant = false;

            autocomplete.removeSelected($scope.transactions);
            autocomplete.removeSelected($scope.autocomplete.transactions);
        };

    }

})();