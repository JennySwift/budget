angular.module('budgetApp')
    .directive('filterTypesDirective', function () {
        return {
            scope: {
                'filter': '=filter',
                'filterTab': '=filtertab',
                'runFilter': '&runfilter'
            },
            templateUrl: 'filter-types-template',

            link: function ($scope) {

                $scope.types = ["income", "expense", "transfer"];

            }
        }
    });
