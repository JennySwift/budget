var NewTransaction = Vue.component('new-transaction', {
    template: '#new-transaction-template',
    data: function () {
        return {
            me: me,
            dropdown: {},
            showNewTransaction: false,
            types: ["income", "expense", "transfer"],
            accounts: [],
            favouriteTransactions: [],
            newTransaction: {
                date: {},
                type: 'income',
                account: {}
            },
            env: env,
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
                this.newTransaction.account = this.accounts[0];
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
            this.newTransaction = NewTransactionRepository.clearFields(env, me, this.newTransaction);
        },

        /**
         * This is not for the transaction autocomplete,
         * which is in the TransactionAutocomplete directive.
         * I think it is for the favourite transactions feature.
         */
        fillFields: function () {
            this.newTransaction.description = this.selectedFavouriteTransaction.description;
            this.newTransaction.merchant = this.selectedFavouriteTransaction.merchant;
            this.newTransaction.total = this.selectedFavouriteTransaction.total;
            this.newTransaction.type = this.selectedFavouriteTransaction.type;
            this.newTransaction.account_id = this.selectedFavouriteTransaction.account.id;
            this.newTransaction.budgets = this.selectedFavouriteTransaction.budgets;
        },

        /**
         * Return true if there are errors.
         * @returns {boolean}
         */
        anyErrors: function () {
            var errorMessages = NewTransactionRepository.anyErrors(this.newTransaction);

            if (errorMessages) {
                for (var i = 0; i < errorMessages.length; i++) {
                    $.event.trigger('provide-feedback', [errorMessages[i], 'error']);
                }

                return true;
            }

            return false;
        },

        /**
         * Insert a new transaction
         */
        insertTransaction: function () {
            if (!this.anyErrors()) {
                $.event.trigger('clear-total-changes');

                if (this.newTransaction.type === 'transfer') {
                    this.insertTransferTransactions();
                }
                else {
                    this.insertIncomeOrExpenseTransaction();
                }
            }
        },

        /**
        *
        */
        insertIncomeOrExpenseTransaction: function () {
            $.event.trigger('show-loading');

            var data = TransactionsRepository.setFields(this.newTransaction);

            this.$http.post('/api/transactions', data, function (response) {
                this.transactions.push(response.data);
                $.event.trigger('get-sidebar-totals');
                this.clearNewTransactionFields();
                //this.newTransaction.dropdown = false;

                if (response.multipleBudgets) {
                    $.event.trigger('transaction-created-with-multiple-budgets', [response.data]);
                    $.event.trigger('get-basic-filter-totals');
                }
                else {
                    $.event.trigger('run-filter');
                }

                $.event.trigger('provide-feedback', ['Transaction created', 'success']);
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        insertTransferTransactions: function () {
            this.insertTransferTransaction('from');
            setTimeout(function(){
                this.insertTransferTransaction('to');
            }, 100);
        },

        /**
         *
         */
        insertTransferTransaction: function (direction) {
            $.event.trigger('show-loading');

            var data = TransactionsRepository.setFields(this.newTransaction);

            data.direction = direction;

            if (direction === 'from') {
                data.account_id = data.from_account_id;
            }
            else if ($direction === 'to') {
                data.account_id = data.to_account_id;
            }

            this.$http.post('/api/transactions', data, function (response) {
                    this.transactions.push(response.data);
                    $.event.trigger('get-sidebar-totals');
                    this.clearNewTransactionFields();
                    //this.newTransaction.dropdown = false;

                    if (response.multipleBudgets) {
                        $.event.trigger('transaction-created-with-multiple-budgets', [response.data]);
                        $.event.trigger('get-basic-filter-totals');
                    }
                    else {
                        $.event.trigger('run-filter');
                    }

                    $.event.trigger('provide-feedback', ['Transfer created', 'success']);
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    HelpersRepository.handleResponseError(response);
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
        'tab',
        'transactions'
    ],
    ready: function () {
        this.newTransaction = NewTransactionRepository.getDefaults(this.env, this.accounts);
        this.getAccounts();
        this.getFavouriteTransactions();
        this.listen();
    }
});