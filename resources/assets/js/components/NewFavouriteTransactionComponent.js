var NewFavouriteTransaction = Vue.component('new-favourite-transaction', {
    template: '#new-favourite-transaction-template',
    data: function () {
        return {
            newFavourite: {
                account: {},
                budgets: [],
                type: 'expense'
            },
            showFields: false,
            types: [
                {
                    name: 'credit', value: 'income',
                },
                {
                    name: 'debit', value: 'expense',
                }
            ]
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        insertFavouriteTransaction: function () {
            $.event.trigger('show-loading');
            var data = FavouriteTransactionsRepository.setFields(this.newFavourite);

            this.$http.post('/api/favouriteTransactions', data, function (response) {
                    this.favouriteTransactions.push(response);
                    this.showFields = false;
                    this.emptyFields();
                    $.event.trigger('provide-feedback', ['Favourite created', 'success']);
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    HelpersRepository.handleResponseError(response);
                });
        },

        /**
         * 
         */
        emptyFields: function () {
            this.newFavourite.name = '';
            this.newFavourite.description = '';
            this.newFavourite.merchant = '';
            this.newFavourite.total = '';
            this.newFavourite.budgets = [];
        },

        /**
         * Set the default new favourite account to the first account, when the accounts are loaded
         */
        setNewFavouriteAccount: function () {
            var that = this;
            setTimeout(function () {
                that.newFavourite.account = that.accounts[0];
            }, 500);
        }
    },
    props: [
        'budgets',
        'favouriteTransactions',
        'accounts'
    ],
    ready: function () {
        this.setNewFavouriteAccount();
    }
});
