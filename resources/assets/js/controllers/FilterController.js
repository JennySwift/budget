(function () {

    angular
        .module('budgetApp')
        .controller('FilterController', filter);

    function filter ($rootScope, $scope, FilterFactory) {

        $scope.filterTab = 'show';
        $scope.filter = FilterFactory.filter;

        $scope.runFilter = function () {
            $rootScope.$emit('runFilter');
        };

        $rootScope.$on('runFilter', function (event, data) {
            $rootScope.$emit('getFilterBasicTotals');
            if ($scope.tab === 'transactions') {
                $scope.$emit('filterTransactions', $scope.filter);
            }
            else {
                $scope.$emit('getGraphTotals');
            }
        });

    }

})();