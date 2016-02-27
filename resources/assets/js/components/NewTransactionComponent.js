var NewTransaction = Vue.component('new-transaction', {
    template: '#new-transaction-template',
    data: function () {
        return {
            dropdown: {},
            showNewTransaction: false,
            types: ["income", "expense", "transfer"],
            accounts: [],
            favouriteTransactions: [],
            newTransaction: {
                date: {}
            },
            env: '',
            colors: {
                newTransaction: {}
            },
        };
    },
    components: {},
    methods: {

        /**
        *
        */
        getAccounts: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/accounts', function (response) {
                this.accounts = response;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
        *
        */
        getFavouriteTransactions: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/favouriteTransactions', function (response) {
                this.favouriteTransactions = response;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

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
            var $errorMessages = NewTransactionRepository.anyErrors(this.newTransaction);

            if ($errorMessages) {
                for (var i = 0; i < $errorMessages.length; i++) {
                    $.event.trigger('provide-feedback', [$errorMessages[i], 'error']);
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
            if ($keycode !== 13 || this.anyErrors()) {
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
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('toggle-new-transaction', function (event) {
                that.showNewTransaction = !that.showNewTransaction;
            });
        }

    },
    props: [
        'tab'
    ],
    ready: function () {
        this.newTransaction = NewTransactionRepository.getDefaults(this.env, this.accounts);
        this.getAccounts();
        this.getFavouriteTransactions();
        this.listen();
    }
});