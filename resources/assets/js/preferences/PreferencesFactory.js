app.factory('PreferencesFactory', function ($http) {
    return {
        savePreferences: function (preferences) {
            var url = '/api/users/' + me.id;
            var data = {
                preferences: preferences
            };

            return $http.put(url, data);
        },
        updateColors: function ($colors) {
            var $url = 'api/update/colors';
            var $description = 'colors';
            var $data = {
                description: $description,
                colors: $colors
            };

            return $http.post($url, $data);
        }
    };
});