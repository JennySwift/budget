var FavouriteTransactions = Vue.component('favourite-transactions', {
    template: '#favourite-transactions-template',
    data: function () {
        return {
            favouriteTransactions: favouriteTransactions,
            accounts: accounts,
            budgets: budgets,
            newFavourite: {
                budgets: []
            },
        };
    },
    components: {},
    methods: {
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