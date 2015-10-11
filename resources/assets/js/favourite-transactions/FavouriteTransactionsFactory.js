angular.module('budgetApp')
    .factory('FavouriteTransactionsFactory', function ($http) {
        return {
            insert: function ($newFavourite) {
                var $url = '/api/favouriteTransactions';

                $newFavourite.budget_ids = _.pluck($newFavourite.budgets, 'id');

                return $http.post($url, $newFavourite);
            },
            destroy: function ($favourite) {
                var $url = '/api/favouriteTransactions/' + $favourite.id;

                return $http.delete($url);
            }
        }
    });