app.factory('PreferencesFactory', function ($http) {
    return {
        savePreferences: function ($preferences) {
            var $url = 'api/update/settings';
            var $data = $preferences;

            return $http.post($url, $data);
        },
        insertOrUpdateDateFormat: function ($new_format) {
            var $url = 'api/insert/insertOrUpdateDateFormat';
            var $data = {
                new_format: $new_format
            };

            return $http.post($url, $data);
        }
    };
});