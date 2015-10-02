angular.module('budgetApp')
    .directive('filterTagsDirective', function ($rootScope) {
        return {
            scope: {
                'filter': '=filter',
                'filterTab': '=filtertab',
                'runFilter': '&runfilter',
                'budgets': '=budgets'
            },
            templateUrl: 'filter-tags-template',

            link: function ($scope) {

                $scope.$watchCollection('filter.budgets.in.and', function (newValue, oldValue) {
                    if (newValue === oldValue) {
                        return;
                    }
                    $rootScope.$emit('runFilter');
                });

                $scope.$watchCollection('filter.budgets.in.or', function (newValue, oldValue) {
                    if (newValue === oldValue) {
                        return;
                    }
                    $rootScope.$emit('runFilter');
                });

                $scope.$watchCollection('filter.budgets.out', function (newValue, oldValue) {
                    if (newValue === oldValue) {
                        return;
                    }
                    $rootScope.$emit('runFilter');
                });

                /**
                 * $type1 is 'in' or 'out'.
                 * $type2 is 'and' or 'or'.
                 * @param $type1
                 * @param $type2
                 */
                $scope.clearTagField = function ($type1, $type2) {
                    if ($type2) {
                        $scope.filter.budgets[$type1][$type2] = [];
                    }
                    else {
                        $scope.filter.budgets[$type1] = [];
                    }
                };

            }
        }
    });
