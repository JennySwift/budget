(function () {

    angular
        .module('budgetApp')
        .controller('FilterController', filter);

    function filter ($rootScope, $scope, FilterFactory) {

        $scope.filterTab = 'show';
        $scope.filter = FilterFactory.filter;
        $scope.savedFilters = savedFilters;

        //Doing this because $scope.savedFilters was updating when I didn't want it to.
        //If the user hit the prev or next buttons, then used the saved filter again,
        //the saved filter was modified and not the original saved filter.
        //I think because I set the filter ng-model to the saved filter in the filter factory.
        var $preservedSavedFilters = angular.copy(savedFilters);

        $scope.runFilter = function () {
            $rootScope.$emit('runFilter');
        };

        $rootScope.$on('resetFilterInFilterController', function () {
            $scope.filter = FilterFactory.filter;
        });

        $rootScope.$on('runFilter', function (event, data) {
            $rootScope.$emit('getFilterBasicTotals');
            if ($scope.tab === 'transactions') {
                $scope.$emit('filterTransactions', $scope.filter);
            }
            else {
                $scope.$emit('getGraphTotals');
            }
        });

        $rootScope.$on('newSavedFilter', function (event, savedFilter) {
            $scope.savedFilters.push(savedFilter);
            $preservedSavedFilters.push(savedFilter);
        });

        /**
         * I am using the id and a clone, so that the savedFilter
         * doesn't change (with actions such as next/prev button clicks)
         * unless deliberately saved again.
         * @param $savedFilterClone
         */
        $scope.chooseSavedFilter = function ($savedFilter) {
            var $preservedSavedFilter = _.findWhere($preservedSavedFilters, {id: $savedFilter.id});
            var $clone = angular.copy($preservedSavedFilter);
            FilterFactory.chooseSavedFilter($clone.filter);
            $scope.filter = FilterFactory.filter;
            $rootScope.$emit('runFilter');
        };

    }

})();