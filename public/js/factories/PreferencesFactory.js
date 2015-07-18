app.factory('PreferencesFactory', function ($http) {
    return {
        savePreferences: function ($preferences) {
            var $url = 'update/settings';
            var $data = $preferences;

            return $http.post($url, $data);
        }
    };
});