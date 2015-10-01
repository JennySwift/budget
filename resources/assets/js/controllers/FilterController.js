(function () {

    angular
        .module('budgetApp')
        .controller('FilterController', filter);

    function filter ($rootScope, $scope, FilterFactory) {

        $scope.types = ["income", "expense", "transfer"];
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
                    $scope.graphTotals = response.data;
                    calculateGraphFigures();
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                })
        };

        function calculateGraphFigures () {
            $scope.graphFigures = FilterFactory.calculateGraphFigures($scope.graphTotals);
        }

    }

})();