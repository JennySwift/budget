var UpdateFavouriteTransaction = Vue.component('update-favourite-transaction', {
    template: '#update-favourite-transaction-template',
    data: function () {
        return {
            showPopup: false,
            selectedFavourite: {},
            types: ["income", "expense", "transfer"],
        };
    },
    components: {},
    methods: {

        /**
        *
        */
        closePopup: function ($event) {
            HelpersRepository.closePopup($event, this);
        },

        /**
        *
        */
        updateFavouriteTransaction: function () {
            $.event.trigger('show-loading');

            var data = FavouriteTransactionsRepository.setFields(this.selectedFavourite);

            this.$http.put('/api/favouriteTransactions/' + this.selectedFavourite.id, data, function (response) {
                this.jsUpdateFavouriteTransaction(response);
                this.showPopup = false;
                $.event.trigger('provide-feedback', ['Favourite transaction updated', 'success']);
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         * @param response
         */
        jsUpdateFavouriteTransaction: function (response) {
            var index = _.indexOf(this.favouriteTransactions, _.findWhere(this.favouriteTransactions, {id: this.selectedFavourite.id}));

            this.favouriteTransactions[index].name = response.name;
            this.favouriteTransactions[index].type = response.type;
            this.favouriteTransactions[index].description = response.description;
            this.favouriteTransactions[index].merchant = response.merchant;
            this.favouriteTransactions[index].total = response.total;
            this.favouriteTransactions[index].account = response.account;
            this.favouriteTransactions[index].budgets = response.budgets;
        },

        /**
         *
         */
        deleteFavouriteTransaction: function () {
            if (confirm("Are you sure?")) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/favouriteTransactions/' + this.selectedFavourite.id, function (response) {
                    this.favouriteTransactions = _.without(this.favouriteTransactions, this.selectedFavourite);
                    $.event.trigger('provide-feedback', ['Favourite transaction deleted', 'success']);
                    this.showPopup = false;
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    HelpersRepository.handleResponseError(response);
                });
            }
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('show-update-favourite-transaction-popup', function (event, favourite) {
                that.selectedFavourite = favourite;
                that.showPopup = true;
            });
        }
    },
    props: [
        'budgets',
        'accounts',
        'favouriteTransactions'
    ],
    ready: function () {
        this.listen();
    }
});
