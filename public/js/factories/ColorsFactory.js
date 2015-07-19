app.factory('ColorsFactory', function ($http) {
	return {
		/**
		 * select
		 */
		
		getColors: function () {
			var $url = 'select/colors';
			var $description = 'colors';
			var $data = {
				description: $description,
			};
			
			return $http.post($url, $data);
		},
		

		/**
		 * update
		 */

		updateColors: function ($colors) {
			var $url = 'update/colors';
			var $description = 'colors';
			var $data = {
				description: $description,
				colors: $colors
			};
			
			return $http.post($url, $data);
		},
	};
});
