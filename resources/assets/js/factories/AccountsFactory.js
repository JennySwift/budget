app.factory('AccountsFactory', function ($http) {
    return {
        getAccounts: function () {

            var $url = '/api/accounts';

            return $http.get($url);
        },
        insertAccount: function () {
            var $url = '/api/accounts';
            var $data = {
                name: $(".new_account_input").val()
            };

            return $http.post($url, $data);
        },
        updateAccountName: function ($account) {
            var $url = $account.path;
            var $data = { name: $account.name };

            return $http.put($url, $data);
        },
        deleteAccount: function ($account) {
            var $url = $account.path;

            return $http.delete($url);
        }

    };
});