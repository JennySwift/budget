var app = angular.module('budgetApp');

(function () {

    app.controller('AccountsController', function ($scope, $http, AccountsFactory) {

        $scope.autocomplete = {};
        $scope.edit_account = false;
        $scope.accounts = accounts;
        $scope.edit_account_popup = {};

        $scope.getAccounts = function () {
            $scope.showLoading();
            AccountsFactory.getAccounts()
                .then(function (response) {
                    $scope.accounts = response.data;
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };

        $scope.insertAccount = function ($keycode) {
            if ($keycode !== 13) {
                return;
            }

            $scope.showLoading();
            AccountsFactory.insertAccount()
                .then(function (response) {
                    $scope.getAccounts();
                    $scope.provideFeedback('Account added');
                    $("#new_account_input").val("");
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };

        $scope.showEditAccountPopup = function ($account_id, $account_name) {
            $scope.edit_account_popup.id = $account_id;
            $scope.edit_account_popup.name = $account_name;
            $scope.show.popups.edit_account = true;
        };

        $scope.updateAccount = function () {
            $scope.showLoading();
            AccountsFactory.updateAccountName($scope.edit_account_popup.id, $scope.edit_account_popup.name)
                .then(function (response) {
                    $scope.getAccounts();
                    $scope.provideFeedback('Account edited');
                    $scope.show.popups.edit_account = false;
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };

        $scope.deleteAccount = function ($account) {
            if (confirm("Are you sure you want to delete this account?")) {
                $scope.showLoading();
                AccountsFactory.deleteAccount($account)
                    .then(function (response) {
                        $scope.getAccounts();
                        $scope.provideFeedback('Account deleted');
                        $scope.hideLoading();
                    })
                    .catch(function (response) {
                        $scope.responseError(response);
                    });
            }
        };

    }); //end controller

})();