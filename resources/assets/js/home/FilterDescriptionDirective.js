/**
 * Much the same as FilterMerchantDirective
 */
angular.module('budgetApp')
    .directive('filterDescriptionDirective', function ($rootScope) {
        return {
            scope: {
                'filter': '=filter',
                'filterTab': '=filtertab',
                'runFilter': '&runfilter'
            },
            templateUrl: 'filter-description-template',

            link: function ($scope) {

                $scope.filterDescriptionOrMerchant = function ($keycode) {
                    if ($keycode !== 13) {
                        return false;
                    }
                    $scope.resetOffset();
                    $rootScope.$emit('runFilter');
                };

                $scope.resetOffset = function () {
                    $scope.filter.offset = 0;
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
