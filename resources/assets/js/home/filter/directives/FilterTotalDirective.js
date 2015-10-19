angular.module('budgetApp')
    .directive('filterTotalDirective', function ($rootScope) {
        return {
            scope: {
                'filter': '=filter',
                'filterTab': '=filtertab',
                'runFilter': '&runfilter'
                //'clearFilterField': '&clearfilterfield'
            },
            templateUrl: 'filter-total-template',

            link: function ($scope) {

                $scope.filterTotal = function ($keycode) {
                    if ($keycode !== 13) {
                        return false;
                    }
                    $rootScope.$emit('runFilter');
                };

                /**
                 * $type is either 'in' or 'out'
                 *
                 * @DO:
                 * This method is duplicated in other parts of the filter, but
                 * for some reason when I had it in my FilterController, both
                 * parameters were undefined.
                 *
                 * @param $field
                 * @param $type
                 */
                $scope.clearFilterField = function ($field, $type) {
                    $scope.filter[$field][$type] = "";
                    $rootScope.$emit('runFilter');
                };

            }
        }
    });
