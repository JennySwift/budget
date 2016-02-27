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


        insertFavouriteTransaction: function () {
            $scope.showLoading();
            FavouriteTransactionsFactory.insert($scope.newFavourite)
                .then(function (response) {
                    $scope.favouriteTransactions.push(response.data.data);
                    $rootScope.$broadcast('provideFeedback', 'Favourite added');
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        },

        deleteFavouriteTransaction: function ($favourite) {
            if (confirm("Are you sure?")) {
                $scope.showLoading();
                FavouriteTransactionsFactory.destroy($favourite)
                    .then(function (response) {
                        $scope.favouriteTransactions = _.without($scope.favouriteTransactions, $favourite);
                        $rootScope.$broadcast('provideFeedback', 'Favourite deleted');
                        $scope.hideLoading();
                    })
                    .catch(function (response) {
                        $scope.responseError(response);
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

//insert: function ($newFavourite) {
//    var $url = '/api/favouriteTransactions';
//
//    $newFavourite.budget_ids = _.pluck($newFavourite.budgets, 'id');
//
//    return $http.post($url, $newFavourite);
//},
//destroy: function ($favourite) {
//    var $url = '/api/favouriteTransactions/' + $favourite.id;
//
//    return $http.delete($url);
//}