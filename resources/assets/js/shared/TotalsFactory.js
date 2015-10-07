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
        },
        getFixedBudgetTotals: function () {
            var $url = '/api/totals/fixedBudget';

            return $http.get($url);
        },
        getFlexBudgetTotals: function () {
            var $url = '/api/totals/flexBudget';

            return $http.get($url);
        },
        getUnassignedBudgetTotals: function () {
            var $url = '/api/totals/unassignedBudget';

            return $http.get($url);
        }

    };
});