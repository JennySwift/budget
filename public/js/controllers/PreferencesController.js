(function () {

    angular
        .module('budgetApp')
        .controller('PreferencesController', preferences);

    function preferences ($scope, $http) {
        /**
         * scope properties
         */

        $scope.preferences = {};

        $scope.savePreferences = function () {
            PreferencesFactory.savePreferences($scope.me.settings)
                .then(function (response) {
                    //$scope. = response.data;
                })
                .catch(function (response) {

                });
        };

    }

})();