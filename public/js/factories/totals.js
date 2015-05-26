app.factory('totals', function ($http) {
	return {
		/**
		 * select
		 */

		basicTotals: function () {
			var $url = 'totals/basic';
			var $description = 'basic totals';
			var $data = {
				description: $description
			};
			
			return $http.post($url, $data);
		},
		budget: function () {
			var $url = 'totals/budget';
			var $description = 'budget totals';
			var $data = {
				description: $description
			};
			
			return $http.post($url, $data);
		},
		
	};
		
});