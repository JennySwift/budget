app.factory('TotalsFactory', function ($http) {
    return {

        getTotals: function () {
            var $url = '/api/totals';

            return $http.get($url);
        }

    };
});