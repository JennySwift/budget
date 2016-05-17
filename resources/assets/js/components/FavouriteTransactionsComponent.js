var FavouriteTransactionsPage = Vue.component('favourite-transactions', {
    template: '#favourite-transactions-page-template',
    data: function () {
        return {
            favouriteTransactions: [],
            accountsRepository: AccountsRepository.state,
            budgets: [],
            newFavourite: {
                budgets: []
            },
        };
    },
    components: {},
    methods: {

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
        getBudgets: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/budgets', function (response) {
                this.budgets = response;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         * @param favouriteTransaction
         */
        showUpdateFavouriteTransactionPopup: function (favouriteTransaction) {
            $.event.trigger('show-update-favourite-transaction-popup', [favouriteTransaction]);
        },

    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        this.getFavouriteTransactions();
        this.getBudgets();
    }
});

//destroy: function ($favourite) {
//    var $url = '/api/favouriteTransactions/' + $favourite.id;
//
//    return $http.delete($url);
//}