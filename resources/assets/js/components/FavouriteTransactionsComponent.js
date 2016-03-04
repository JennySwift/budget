var FavouriteTransactionsPage = Vue.component('favourite-transactions', {
    template: '#favourite-transactions-page-template',
    data: function () {
        return {
            favouriteTransactions: [],
            accounts: [],
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
        */
        deleteFavouriteTransaction: function (favourite) {
            if (confirm("Are you sure?")) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/favouriteTransactions/' + favourite.id, function (response) {
                    this.favouriteTransactions = _.without(this.favouriteTransactions, favourite);
                    //var index = _.indexOf(this.favourites, _.findWhere(this.favourites, {id: this.favourite.id}));
                    //this.favourites = _.without(this.favourites, this.favourites[index]);
                    $.event.trigger('provide-feedback', ['Favourite transaction deleted', 'success']);
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    HelpersRepository.handleResponseError(response);
                });
            }
        },

    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        this.getFavouriteTransactions();
        this.getAccounts();
        this.getBudgets();
    }
});

//destroy: function ($favourite) {
//    var $url = '/api/favouriteTransactions/' + $favourite.id;
//
//    return $http.delete($url);
//}