app.factory('UsersFactory', function ($http) {
    return {
        deleteAccount: function ($user) {
            var $url = $user.path;

            return $http.delete($url);
        }

    };
});