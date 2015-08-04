(function () {

    angular
        .module('budgetApp')
        .controller('PreferencesController', preferences);

    function preferences ($scope, $http, PreferencesFactory, FeedbackFactory) {
        /**
         * scope properties
         */

        $scope.preferences = {};

        $scope.responseError = function (response) {
            if (response.status === 503) {
                FeedbackFactory.provideFeedback('Sorry, application under construction. Please try again later.');
            }
            else {
                FeedbackFactory.provideFeedback('There was an error');
            }
        };

        $scope.savePreferences = function () {
            PreferencesFactory.savePreferences($scope.me.settings)
                .then(function (response) {
                    //$scope. = response.data;
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };

    }

})();