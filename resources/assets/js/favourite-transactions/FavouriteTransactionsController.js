var app = angular.module('budgetApp');

(function () {

    app.controller('FavouriteTransactionsController', function ($rootScope, $scope, FavouriteTransactionsFactory) {

        $scope.favouriteTransactions = favouriteTransactions;
        $scope.accounts = accounts;
        $scope.budgets = budgets;
        $scope.newFavourite = {
          budgets: []
        };

        $scope.insertFavouriteTransaction = function () {
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
        };

        $scope.deleteFavouriteTransaction = function ($favourite) {
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

        };

    });

})();