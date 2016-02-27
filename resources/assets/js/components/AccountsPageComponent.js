var AccountsPage = Vue.component('accounts-page', {
    template: '#accounts-page-template',
    data: function () {
        return {
            accounts: accounts,
            edit_account_popup: {},
        };
    },
    components: {},
    methods: {
        insertAccount: function ($keycode) {
            if ($keycode !== 13) {
                return;
            }

            $scope.showLoading();
            AccountsFactory.insertAccount()
                .then(function (response) {
                    $scope.accounts.push(response.data);
                    $rootScope.$broadcast('provideFeedback', 'Account added');
                    $("#new_account_input").val("");
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        },

        showEditAccountPopup: function ($account) {
            $scope.edit_account_popup = $account;
            $scope.show.popups.edit_account = true;
        },

        updateAccount: function () {
            $scope.showLoading();
            AccountsFactory.updateAccountName($scope.edit_account_popup)
                .then(function (response) {
                    var $index = _.indexOf($scope.accounts, _.findWhere($scope.accounts, {id: $scope.edit_account_popup.id}));
                    $scope.accounts[$index] = response.data;
                    $rootScope.$broadcast('provideFeedback', 'Account edited');
                    $scope.show.popups.edit_account = false;
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        },

        deleteAccount: function ($account) {
            if (confirm("Are you sure you want to delete this account?")) {
                $scope.showLoading();
                AccountsFactory.deleteAccount($account)
                    .then(function (response) {
                        $scope.accounts = _.without($scope.accounts, $account);
                        $rootScope.$broadcast('provideFeedback', 'Account deleted');
                        $scope.hideLoading();
                    })
                    .catch(function (response) {
                        $scope.responseError(response);
                    });
            }
        },
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {

    }
});