(function () {

    angular
        .module('budgetApp')
        .controller('PreferencesController', preferences);

    function preferences ($rootScope, $scope, PreferencesFactory) {

        $scope.colors = me.preferences.colors;

        $scope.$watchCollection('colors', function (newValue) {
            $("#income-color-picker").val(newValue.income);
            $("#expense-color-picker").val(newValue.expense);
            $("#transfer-color-picker").val(newValue.transfer);
        });

        $scope.preferences = {};

        $scope.savePreferences = function () {
            PreferencesFactory.savePreferences($scope.me.preferences)
                .then(function (response) {
                    $rootScope.$broadcast('provideFeedback', 'Preferences saved');
                    //$scope. = response.data;
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };

        $scope.defaultColor = function ($type, $default_color) {
            if ($type === 'income') {
                $scope.colors.income = $default_color;
            }
            else if ($type === 'expense') {
                $scope.colors.expense = $default_color;
            }
            else if ($type === 'transfer') {
                $scope.colors.transfer = $default_color;
            }
        };
    }

})();