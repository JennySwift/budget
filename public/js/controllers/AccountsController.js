var app = angular.module('budgetApp');

(function () {

    app.controller('AccountsController', function ($scope, $http, AccountsFactory) {

        /**
         * scope properties
         */

        $scope.me = me;
        $scope.autocomplete = {};
        $scope.edit_account = false;
        $scope.show = {
            popups: {}
        };
        $scope.accounts = accounts;
        $scope.edit_account_popup = {};

        /**
         * select
         */

        $scope.getAccounts = function () {
            AccountsFactory.getAccounts().then(function (response) {
                $scope.accounts = response.data;
            });
        };

        /**
         * insert
         */

        $scope.insertAccount = function ($keycode) {
            if ($keycode !== 13) {
                return;
            }

            AccountsFactory.insertAccount().then(function (response) {
                $scope.getAccounts();
                $("#new_account_input").val("");
            });
        };

        /**
         * update
         */

        $scope.showEditAccountPopup = function ($account_id, $account_name) {
            $scope.edit_account_popup.id = $account_id;
            $scope.edit_account_popup.name = $account_name;
            $scope.show.popups.edit_account = true;
        };

        $scope.updateAccount = function () {
            AccountsFactory.updateAccountName($scope.edit_account_popup.id, $scope.edit_account_popup.name).then(function (response) {
                $scope.getAccounts();
                $scope.show.popups.edit_account = false;
            });
        };

        /**
         * delete
         */

        $scope.deleteAccount = function ($account_id) {
            if (confirm("Are you sure you want to delete this account?")) {
                AccountsFactory.deleteAccount($account_id).then(function (response) {
                    $scope.getAccounts();
                });
            }
        };

        /**
         * other
         */

        $scope.closePopup = function ($event, $popup) {
            var $target = $event.target;
            if ($target.className === 'popup-outer') {
                $scope.show.popups[$popup] = false;
            }
        };

    }); //end controller

})();