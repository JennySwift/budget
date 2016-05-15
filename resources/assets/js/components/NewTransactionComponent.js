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
            newTransaction: {},
            selectedFavouriteTransaction: {},
            //newTransaction: {
            //    date: {},
            //    type: 'income',
            //    account: {}
            //},
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
                this.newTransaction = NewTransactionRepository.getDefaults(this.env, this.accounts);
                //this.newTransaction.account = this.accounts[0];
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
            this.newTransaction.budgets = this.selectedFavouriteTransaction.budgets;

            if (this.newTransaction.type === 'transfer') {
                this.newTransaction.fromAccount = this.selectedFavouriteTransaction.fromAccount;
                this.newTransaction.toAccount = this.selectedFavouriteTransaction.toAccount;
            }
            else {
                this.newTransaction.account = this.selectedFavouriteTransaction.account;
            }
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
         *
         */
        insertTransactionPreparation: function () {
            if (!this.anyErrors()) {
                $.event.trigger('clear-total-changes');

                if (this.newTransaction.type === 'transfer') {
                    var that = this;
                    this.insertTransaction('from');
                    setTimeout(function(){
                        that.insertTransaction('to');
                    }, 100);
                }
                else {
                    this.insertTransaction();
                }
            }
        },

        /**
        *
        */
        insertTransaction: function (direction) {
            $.event.trigger('show-loading');

            var data = TransactionsRepository.setFields(this.newTransaction);

            if (direction) {
                //It is a transfer transaction
                data.direction = direction;

                if (direction === 'from') {
                    data.account_id = this.newTransaction.fromAccount.id;
                }
                else if (direction === 'to') {
                    data.account_id = this.newTransaction.toAccount.id;
                }
            }

            this.$http.post('/api/transactions', data, function (response) {
                this.insertTransactionResponse(response);
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         * @param response
         */
        insertTransactionResponse: function (response) {
            $.event.trigger('get-sidebar-totals');
            this.clearNewTransactionFields();
            //this.newTransaction.dropdown = false;

            if (response.multipleBudgets) {
                $.event.trigger('show-allocation-popup', [response, true]);
                //We'll run the filter after the allocation has been dealt with
            }
            else {
                $.event.trigger('run-filter');
            }

            $.event.trigger('provide-feedback', ['Transaction created', 'success']);
            $.event.trigger('hide-loading');
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
        'transactions',
        'budgets'
    ],
    ready: function () {
        this.getAccounts();
        this.getFavouriteTransactions();
        this.listen();
    }
});