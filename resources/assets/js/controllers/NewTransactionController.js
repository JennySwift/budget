(function () {

    angular
        .module('budgetApp')
        .controller('NewTransactionController', newTransaction);

    function newTransaction ($scope, TransactionsFactory, FilterFactory) {

        $scope.filterFactory = FilterFactory;
        $scope.dropdown = {};
        $scope.types = ["income", "expense", "transfer"];

        $scope.new_transaction = {
            type: 'income',
            account_id: 1,
            date: {
                entered: 'today'
            },
            merchant: '',
            description: '',
            reconciled: false,
            multiple_budgets: false,
            budgets: []
        };

        /**
         * Fill in the new transaction fields if development environment
         */
        if ($scope.env === 'local') {
            $scope.new_transaction.total = 10;
            $scope.new_transaction.type = 'expense';
            $scope.new_transaction.date.entered = 'today';
            $scope.new_transaction.merchant = 'some merchant';
            $scope.new_transaction.description = 'some description';
            $scope.new_transaction.budgets = [
                {
                    id: '2',
                    name: 'business',
                    type: 'fixed'
                },
                {
                    id: '4',
                    name: 'busking',
                    type: 'flex'
                }
            ];
        }

        $scope.accounts = accounts_response;
        if ($scope.accounts[0]) {
            //this if check is to get rid of the error for a new user who does not yet have any accounts.
            $scope.new_transaction.account_id = $scope.accounts[0].id;
            $scope.new_transaction.from_account_id = $scope.accounts[0].id;
            $scope.new_transaction.to_account_id = $scope.accounts[0].id;
        }

        /**
         * Clear new transaction fields
         */
        function clearNewTransactionFields () {
            if ($scope.env !== 'local') {
                $scope.new_transaction.budgets = [];
            }

            if (me.preferences.clearFields) {
                $scope.new_transaction.total = '';
                $scope.new_transaction.description = '';
                $scope.new_transaction.merchant = '';
                $scope.new_transaction.reconciled = false;
                $scope.new_transaction.multiple_budgets = false;
            }
        }

        /**
         * Return true if there are errors.
         * @returns {boolean}
         */
        function anyErrors () {
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
        }

        /**
         * Insert a new transaction
         * @param $keycode
         */
        $scope.insertTransaction = function ($keycode) {
            if ($keycode !== 13 || anyErrors()) {
                return;
            }

            $scope.clearTotalChanges();

            if ($scope.new_transaction.type === 'transfer') {
                insertTransferTransactions();
            }
            else {
                insertIncomeOrExpenseTransaction();
            }
        };

        function insertIncomeOrExpenseTransaction () {
            $scope.showLoading();
            TransactionsFactory.insertIncomeOrExpenseTransaction($scope.new_transaction)
                .then(function (response) {
                    var $transaction = response.data.data;
                    $scope.provideFeedback('Transaction added');
                    clearNewTransactionFields();
                    $scope.new_transaction.dropdown = false;
                    $scope.getSideBarTotals();

                    if ($transaction.multipleBudgets) {
                        $scope.handleAllocationForNewTransaction($transaction);
                    }
                    else {
                        $scope.runFilter();
                    }

                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        }

        function insertTransferTransactions () {
            insertTransferTransaction('from');
            setTimeout(function(){
                insertTransferTransaction('to');
            }, 100);
        }

        function insertTransferTransaction ($direction) {
            $scope.showLoading();
            TransactionsFactory.insertTransferTransaction($scope.new_transaction, $direction)
                .then(function (response) {
                    $scope.provideFeedback('Transfer added');
                    clearNewTransactionFields();
                    $scope.getSideBarTotals();
                    $scope.runFilter();
                    $scope.new_transaction.dropdown = false;

                    //Todo: get filter stuff
                    //FilterFactory.updateDataForControllers(response.data);
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        }
    }

})();