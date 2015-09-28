(function () {

    angular
        .module('budgetApp')
        .controller('NewTransactionController', newTransaction);

    function newTransaction ($scope, NewTransactionFactory, TransactionsFactory, FilterFactory) {

        $scope.dropdown = {};
        $scope.types = ["income", "expense", "transfer"];
        $scope.accounts = accounts_response;
        $scope.new_transaction = NewTransactionFactory.getDefaults($scope.env, $scope.accounts);

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
                        $scope.getFilterBasicTotals();
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
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        }
    }

})();