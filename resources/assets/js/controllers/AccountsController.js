var app = angular.module('budgetApp');

(function () {

    app.controller('AccountsController', function ($scope, $http, AccountsFactory, FeedbackFactory) {

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
        $scope.feedback_messages = [];
        $scope.feedbackFactory = FeedbackFactory;
        $scope.edit_account_popup = {};

        $scope.$watch('feedbackFactory.data', function (newValue, oldValue, scope) {
            if (newValue && newValue.message) {
                scope.provideFeedback(newValue.message);
            }
        });

        $scope.provideFeedback = function ($message) {
            $scope.feedback_messages.push($message);
            setTimeout(function () {
                $scope.feedback_messages = _.without($scope.feedback_messages, $message);
                $scope.$apply();
            }, 3000);
        };

        $scope.responseError = function (response) {
            if (response.status === 503) {
                FeedbackFactory.provideFeedback('Sorry, application under construction. Please try again later.');
            }
            else {
                FeedbackFactory.provideFeedback('There was an error');
            }
        };

        /**
         * select
         */

        $scope.getAccounts = function () {
            AccountsFactory.getAccounts()
                .then(function (response) {
                    $scope.accounts = response.data;
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };

        /**
         * insert
         */

        $scope.insertAccount = function ($keycode) {
            if ($keycode !== 13) {
                return;
            }

            AccountsFactory.insertAccount()
                .then(function (response) {
                    $scope.getAccounts();
                    $("#new_account_input").val("");
                })
                .catch(function (response) {
                    $scope.responseError(response);
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
            AccountsFactory.updateAccountName($scope.edit_account_popup.id, $scope.edit_account_popup.name)
                .then(function (response) {
                    $scope.getAccounts();
                    $scope.show.popups.edit_account = false;
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };

        /**
         * delete
         */

        $scope.deleteAccount = function ($account_id) {
            if (confirm("Are you sure you want to delete this account?")) {
                AccountsFactory.deleteAccount($account_id)
                    .then(function (response) {
                        $scope.getAccounts();
                    })
                    .catch(function (response) {
                        $scope.responseError(response);
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