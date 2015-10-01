angular.module('budgetApp')
    .directive('filterDateDirective', function ($rootScope) {
        return {
            scope: {
                'filter': '=filter',
                'filterTab': '=filtertab',
                'runFilter': '&runfilter'
            },
            templateUrl: 'filter-date-template',

            link: function ($scope) {

                $scope.filterDate = function ($keycode) {
                    if ($keycode !== 13) {
                        return false;
                    }
                    $rootScope.$emit('runFilter');
                };

                /**
                 * $type is either 'in' or 'out'
                 * @param $field
                 * @param $type
                 */
                $scope.clearDateField = function ($field, $type) {
                    $scope.filter[$field][$type]['user'] = "";
                    $rootScope.$emit('runFilter');
                };
            }
        }
    });
