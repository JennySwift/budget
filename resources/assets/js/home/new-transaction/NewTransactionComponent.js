var NewTransaction = Vue.component('new-transaction', {
    template: '#new-transaction-template',
    data: function () {
        return {
            dropdown: {},
            types: ["income", "expense", "transfer"],
            accounts: accounts_response,
            favouriteTransactions: favouriteTransactions,
            new_transaction: NewTransactionRepository.getDefaults(env, this.accounts),
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        clearNewTransactionFields: function () {
            this.new_transaction = NewTransactionRepository.clearFields(env, me, this.new_transaction);
        },

        /**
         * This is not for the transaction autocomplete,
         * which is in the TransactionAutocomplete directive.
         * I think it is for the favourite transactions feature.
         */
        fillFields: function () {
            this.new_transaction.description = this.selectedFavouriteTransaction.description;
            this.new_transaction.merchant = this.selectedFavouriteTransaction.merchant;
            this.new_transaction.total = this.selectedFavouriteTransaction.total;
            this.new_transaction.type = this.selectedFavouriteTransaction.type;
            this.new_transaction.account_id = this.selectedFavouriteTransaction.account.id;
            this.new_transaction.budgets = this.selectedFavouriteTransaction.budgets;
        },

        /**
         * Return true if there are errors.
         * @returns {boolean}
         */
        anyErrors: function () {
            var $errorMessages = NewTransactionRepository.anyErrors(this.new_transaction);

            if ($errorMessages) {
                for (var i = 0; i < $errorMessages.length; i++) {
                    $rootScope.$broadcast('provideFeedback', $errorMessages[i], 'error');
                }

                return true;
            }

            return false;
        },

        /**
         * Insert a new transaction
         * @param $keycode
         */
        insertTransaction: function ($keycode) {
            if ($keycode !== 13 || anyErrors()) {
                return;
            }

            this.clearTotalChanges();

            if (this.new_transaction.type === 'transfer') {
                insertTransferTransactions();
            }
            else {
                insertIncomeOrExpenseTransaction();
            }
        },

        insertIncomeOrExpenseTransaction: function () {
            this.showLoading();
            TransactionsFactory.insertIncomeOrExpenseTransaction(this.new_transaction)
                .then(function (response) {
                    var $transaction = response.data.data;
                    $rootScope.$broadcast('provideFeedback', 'Transaction added');
                    clearNewTransactionFields();
                    this.new_transaction.dropdown = false;
                    this.$emit('getSideBarTotals');

                    if ($transaction.multipleBudgets) {
                        this.$emit('handleAllocationForNewTransaction', $transaction);
                        $rootScope.$emit('getFilterBasicTotals');
                    }
                    else {
                        $rootScope.$emit('runFilter');
                    }

                    this.hideLoading();
                })
                .catch(function (response) {
                    this.responseError(response);
                });
        },

        insertTransferTransactions: function () {
            insertTransferTransaction('from');
            setTimeout(function(){
                insertTransferTransaction('to');
            }, 100);
        },

        insertTransferTransaction: function ($direction) {
            this.showLoading();
            TransactionsFactory.insertTransferTransaction(this.new_transaction, $direction)
                .then(function (response) {
                    $rootScope.$broadcast('provideFeedback', 'Transfer added');
                    clearNewTransactionFields();
                    this.$emit('getSideBarTotals');
                    $rootScope.$emit('runFilter');
                    this.new_transaction.dropdown = false;

                    //Todo: get filter stuff
                    this.hideLoading();
                })
                .catch(function (response) {
                    this.responseError(response);
                });
        }

    },
    props: [
        //data to be received from parent
    ],
    ready: function () {

    }
});