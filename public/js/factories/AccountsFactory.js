app.factory('AccountsFactory', function ($http) {
    return {
        getAccounts: function () {
            var $url = 'select/accounts';
            var $description = 'accounts';
            var $data = {
                description: $description
            };

            return $http.post($url, $data);
        },
        insertAccount: function () {
            var $url = '/accounts';
            var $data = {
                name: $(".new_account_input").val()
            };

            return $http.post($url, $data);
        },
        updateAccountName: function ($account_id, $account_name) {
            var $url = 'update/accountName';
            var $description = 'account name';
            var $data = {
                description: $description,
                account_id: $account_id,
                account_name: $account_name
            };

            return $http.post($url, $data);

        },
        deleteAccount: function ($account_id) {
            var $url = 'delete/account';
            var $description = 'account';
            var $data = {
                description: $description,
                account_id: $account_id
            };

            return $http.post($url, $data);
        },

    };
});