(function () {

    angular
        .module('budgetApp')
        .controller('NewTransactionController', newTransaction);

    function newTransaction ($scope, $http, autocomplete, TransactionsFactory, FilterFactory) {
        /**
         * scope properties
         */

        $scope.filterFactory = FilterFactory;

        $scope.dropdown = {};

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

        /**
         * Watches
         */

        $scope.$watch('filterFactory.filter', function (newValue, oldValue, scope) {
            if (newValue) {
                scope.filter = newValue;
            }
        });

        /**
         * Clear new transaction fields
         */
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

        /**
         * Insert a new transaction
         * @param $keycode
         */
        $scope.insertTransaction = function ($keycode) {
            if ($keycode !== 13 || !$scope.errorCheck()) {
                return;
            }

            TransactionsFactory.insertTransaction($scope.new_transaction, $scope.filter)
                .then(function (response) {
                    $scope.provideFeedback('Transaction added');
                    $scope.checkNewTransactionForMultipleBudgets(response);
                    $scope.clearNewTransactionFields();
                    $scope.new_transaction.dropdown = false;

                    FilterFactory.updateDataForControllers(response.data);
                })
                .catch(function (response) {
                    $scope.provideFeedback('There was an error');
                });
        };

        /**
         * See if the transaction that was just entered has multiple budgets.
         * The allocation popup is shown from $scope.multiSearch().
         * @param response
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
    }

})();