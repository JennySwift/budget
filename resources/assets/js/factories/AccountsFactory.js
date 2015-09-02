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
        updateAccountName: function ($account_id, $account_name) {
            var $url = '/api/accounts/' + $account_id;
            var $data = { name: $account_name };

            return $http.put($url, $data);
        },
        deleteAccount: function ($account) {
            console.log($account.path);
            var $url = $account.path;

            return $http.delete($url);
        }

    };
});