app.factory('ColorsFactory', function ($http) {
	return {
		updateColors: function ($colors) {
			var $url = 'update/colors';
			var $description = 'colors';
			var $data = {
				description: $description,
				colors: $colors
			};
			
			return $http.post($url, $data);
		}
	};
});
