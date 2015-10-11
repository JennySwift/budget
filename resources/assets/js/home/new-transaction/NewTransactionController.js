(function () {

    angular
        .module('budgetApp')
        .controller('NewTransactionController', newTransaction);

    function newTransaction ($rootScope, $scope, NewTransactionFactory, TransactionsFactory, FilterFactory) {

        $scope.dropdown = {};
        $scope.types = ["income", "expense", "transfer"];
        $scope.accounts = accounts_response;
        $scope.favouriteTransactions = favouriteTransactions;
        $scope.new_transaction = NewTransactionFactory.getDefaults(env, $scope.accounts);

        function clearNewTransactionFields () {
            $scope.new_transaction = NewTransactionFactory.clearFields(env, me, $scope.new_transaction);
        }

        $scope.fillFields = function () {
            $scope.new_transaction.description = $scope.selectedFavouriteTransaction.description;
            $scope.new_transaction.merchant = $scope.selectedFavouriteTransaction.merchant;
            $scope.new_transaction.total = $scope.selectedFavouriteTransaction.total;
            $scope.new_transaction.type = $scope.selectedFavouriteTransaction.type;
            $scope.new_transaction.account_id = $scope.selectedFavouriteTransaction.account_id;
            //$scope.new_transaction.budgets = $scope.selectedFavouriteTransaction.budgets;
        };

        /**
         * Return true if there are errors.
         * @returns {boolean}
         */
        function anyErrors () {
            var $errorMessages = NewTransactionFactory.anyErrors($scope.new_transaction);

            if ($errorMessages) {
                for (var i = 0; i < $errorMessages.length; i++) {
                    $rootScope.$broadcast('provideFeedback', $errorMessages[i], 'error');
                }

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
                    $rootScope.$broadcast('provideFeedback', 'Transaction added');
                    clearNewTransactionFields();
                    $scope.new_transaction.dropdown = false;
                    $scope.$emit('getSideBarTotals');

                    if ($transaction.multipleBudgets) {
                        $scope.$emit('handleAllocationForNewTransaction', $transaction);
                        $rootScope.$emit('getFilterBasicTotals');
                    }
                    else {
                        $rootScope.$emit('runFilter');
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
                    $rootScope.$broadcast('provideFeedback', 'Transfer added');
                    clearNewTransactionFields();
                    $scope.$emit('getSideBarTotals');
                    $rootScope.$emit('runFilter');
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