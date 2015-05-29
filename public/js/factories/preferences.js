app.factory('preferences', function ($http) {
	return {
		insertOrUpdateDateFormat: function ($new_format) {
			var $url = 'insert/insertOrUpdateDateFormat';
			var $data = {
				new_format: $new_format
			};
			
			return $http.post($url, $data);
		}
	};
});