app.factory('TotalsFactory', function ($http) {
    return {

        /**
         * Get all the totals
         * @returns {*}
         */
        getTotals: function () {
            var $url = '/api/totals';

            return $http.get($url);
        },
        getSideBarTotals: function () {
            var $url = '/api/totals/sidebar';

            return $http.get($url);
        }

    };
});