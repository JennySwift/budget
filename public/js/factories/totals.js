app.factory('totals', function ($http) {
	return {
		/**
		 * select
		 */

		basicTotals: function () {
			var $url = 'totals/basic';
			
			return $http.post($url);
		},
		budget: function () {
			var $url = 'totals/budget';
			
			return $http.post($url);
		},
        basicAndBudget: function () {
            var $url = 'totals/basicAndBudget';

            return $http.post($url);
        }
		
	};
		
});