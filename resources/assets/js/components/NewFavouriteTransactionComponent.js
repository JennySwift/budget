var NewFavouriteTransaction = Vue.component('new-favourite-transaction', {
    template: '#new-favourite-transaction-template',
    data: function () {
        return {
            newFavourite: {
                account: this.accounts[0],
                budgets: []
            }
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
                    $.event.trigger('provide-feedback', ['Favourite created', 'success']);
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    HelpersRepository.handleResponseError(response);
                });
        },
    },
    props: [
        'budgets',
        'favouriteTransactions',
        'accounts'
    ],
    ready: function () {

    }
});
