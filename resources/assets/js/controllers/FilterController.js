(function () {

    angular
        .module('budgetApp')
        .controller('FilterController', filter);

    function filter ($rootScope, $scope, FilterFactory) {

        $scope.filterTab = 'show';
        $scope.filter = FilterFactory.filter;
        $scope.filterTotals = filterBasicTotals;

        $scope.runFilter = function () {
            $rootScope.$emit('runFilter');
        };

        $rootScope.$on('runFilter', function (event, data) {
            $scope.getFilterBasicTotals();
            if ($scope.tab === 'transactions') {
                $scope.$emit('filterTransactions', $scope.filter);
            }
            else {
                $scope.getGraphTotals();
            }
        });

        $scope.getFilterBasicTotals = function () {
            FilterFactory.getBasicTotals($scope.filter)
                .then(function (response) {
                    $scope.filterTotals = response.data;
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                })
        };

        $scope.getGraphTotals = function () {
            FilterFactory.getGraphTotals($scope.filter)
                .then(function (response) {
                    $scope.graphFigures = FilterFactory.calculateGraphFigures(response.data);
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                })
        };

    }

})();