(function () {

    angular
        .module('budgetApp')
        .controller('HomeController', home);

    function home ($scope, TransactionsFactory, PreferencesFactory) {

        $scope.transactionsFactory = TransactionsFactory;
        $scope.page = 'home';

        $scope.colors = me.preferences.colors;

        $scope.$watch('PreferencesFactory.date_format', function (newValue, oldValue) {
            if (!newValue) {
                return;
            }
            $scope.showLoading();
            PreferencesFactory.insertOrUpdateDateFormat(newValue)
                .then(function (response) {
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        });

        $scope.toggleFilter = function () {
            $scope.show.filter = !$scope.show.filter;
        };

    }

})();