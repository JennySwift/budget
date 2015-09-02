(function () {

    angular
        .module('budgetApp')
        .controller('NewTransactionController', newTransaction);

    function newTransaction ($scope, $http, TransactionsFactory, FilterFactory, FeedbackFactory) {
        /**
         * scope properties
         */

        $scope.filterFactory = FilterFactory;
        $scope.dropdown = {};
        //$scope.tags = tags_response;
        $scope.budgets = budgets;
        $scope.types = ["income", "expense", "transfer"];

        $scope.new_transaction = {
            type: 'income',
            account: 1,
            date: {
                entered: 'today'
            },
            merchant: '',
            description: '',
            reconciled: false,
            multiple_budgets: false,
            tags: []
        };

        /**
         * Fill in the new transaction fields if development environment
         */
        if ($scope.env === 'local') {
            $scope.new_transaction.total = 10;
            $scope.new_transaction.date.entered = 'today';
            $scope.new_transaction.merchant = 'some merchant';
            $scope.new_transaction.description = 'some description';
            $scope.new_transaction.tags = [
                //{
                //    id: '1',
                //    name: 'insurance',
                //    //fixed_budget: '10.00',
                //    //flex_budget: null
                //},
                //{
                //    id: '2',
                //    name: 'petrol',
                //    //fixed_budget: null,
                //    //flex_budget: '5'
                //}
            ];
        }

        $scope.accounts = accounts_response;
        if ($scope.accounts[0]) {
            //this if check is to get rid of the error for a new user who does not yet have any accounts.
            $scope.new_transaction.account = $scope.accounts[0].id;
            $scope.new_transaction.from_account = $scope.accounts[0].id;
            $scope.new_transaction.to_account = $scope.accounts[0].id;
        }

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

            if (me.preferences.clearFields) {
                $scope.new_transaction.total = '';
                $scope.new_transaction.description = '';
                $scope.new_transaction.merchant = '';
                $scope.new_transaction.reconciled = false;
                $scope.new_transaction.multiple_budgets = false;
            }
        };

        /**
         * Return true if there are errors.
         * @returns {boolean}
         */
        $scope.anyErrors = function () {
            $errorCount = 0;
            var $messages = [];

            if (!Date.parse($scope.new_transaction.date.entered)) {
                $scope.provideFeedback('Date is not valid', 'error');
                $errorCount++;
            }
            else {
                $scope.new_transaction.date.sql = Date.parse($scope.new_transaction.date.entered).toString('yyyy-MM-dd');
            }

            if ($scope.new_transaction.total === "") {
                $scope.provideFeedback('Total is required', 'error');
                $errorCount++;
            }
            else if (!$.isNumeric($scope.new_transaction.total)) {
                $scope.provideFeedback('Total is not a valid number', 'error');
                $errorCount++;
            }

            if ($errorCount > 0) {
                return true;
            }

            return false;
        };

        /**
         * Insert a new transaction
         * @param $keycode
         */
        $scope.insertTransaction = function ($keycode) {
            if ($keycode !== 13 || $scope.anyErrors()) {
                return;
            }

            $scope.showLoading();
            TransactionsFactory.insertTransaction($scope.new_transaction, $scope.filter)
                .then(function (response) {
                    $scope.provideFeedback('Transaction added');
                    $scope.clearNewTransactionFields();
                    $scope.new_transaction.dropdown = false;
                    FilterFactory.updateDataForControllers(response.data);
                    $scope.checkNewTransactionForMultipleBudgets(response);
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };

        /**
         * See if the transaction that was just entered has multiple budgets.
         * @param response
         */
        $scope.checkNewTransactionForMultipleBudgets = function (response) {
            if (response.data.multiple_budgets) {
                $scope.allocation_popup = response.data.transaction;
                $scope.showAllocationPopupForNewTransaction();
            }
        };

        $scope.showAllocationPopupForNewTransaction = function () {
            var $transaction = $scope.findTransaction();

            if ($transaction) {
                $scope.showAllocationPopup($transaction);
            }
            else {
                //the transaction isn't showing with the current filter settings
                $scope.showAllocationPopup($scope.allocation_popup);
            }
        };

        /**
         * For the allocation popup when a new transaction is entered.
         * Find the transaction that was just entered.
         * This is so that the transaction is updated live
         * when actions are done in the allocation popup.
         * Otherwise it will need a page refresh.
         */
        $scope.findTransaction = function () {
            var $transaction = _.find(FilterFactory.filter_results.transactions, function ($scope_transaction) {
                return $scope_transaction.id === $scope.allocation_popup.id;
            });

            return $transaction;
        };
    }

})();