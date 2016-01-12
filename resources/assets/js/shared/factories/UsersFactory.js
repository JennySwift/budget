app.factory('UsersFactory', function ($http) {
    return {
        deleteAccount: function (user) {
            var url = '/api/users/' + user.id;

            return $http.delete(url);
        }

    };
});