angular.module('budgetApp')
    .directive('filterToolbarDirective', function ($rootScope, FilterFactory) {
        return {
            scope: {
                'filter': '=filter',
                'filterTotals': '=filtertotals'
            },
            templateUrl: 'filter-toolbar-template',

            link: function ($scope) {

                $rootScope.$on('resetFilter', function (event, data) {
                    $scope.filter = FilterFactory.resetFilter();
                    $rootScope.$emit('runFilter');
                });

                $scope.resetFilter = function () {
                    $scope.$emit('resetFilter');
                };

                /**
                 * Updates filter.display_from and filter.display_to values
                 */
                function updateRange () {
                    $scope.filter.display_from = $scope.filter.offset + 1;
                    $scope.filter.display_to = $scope.filter.offset + ($scope.filter.num_to_fetch * 1);
                }

                $scope.changeNumToFetch = function () {
                    updateRange();
                    $rootScope.$emit('runFilter');
                };

                //Todo: I might not need some of this code (not allowing offset to be less than 0)
                // todo: since I disabled the button if that is the case
                $scope.prevResults = function () {
                    //make it so the offset cannot be less than 0.
                    if ($scope.filter.offset - $scope.filter.num_to_fetch < 0) {
                        $scope.filter.offset = 0;
                    }
                    else {
                        $scope.filter.offset-= ($scope.filter.num_to_fetch * 1);
                        updateRange();
                        $rootScope.$emit('runFilter');
                    }
                };

                $scope.nextResults = function () {
                    if ($scope.filter.offset + ($scope.filter.num_to_fetch * 1) > $scope.filterTotals.numTransactions) {
                        //stop it going past the end.
                        return;
                    }

                    $scope.filter.offset+= ($scope.filter.num_to_fetch * 1);
                    updateRange();
                    $rootScope.$emit('runFilter');
                };
            }
        }
    });
