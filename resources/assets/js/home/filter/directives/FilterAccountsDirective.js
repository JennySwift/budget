angular.module('budgetApp')
    .directive('filterAccountsDirective', function () {
        return {
            scope: {
                'filter': '=filter',
                'filterTab': '=filtertab',
                'runFilter': '&runfilter'
            },
            templateUrl: 'filter-accounts-template',

            link: function ($scope) {
                $scope.accounts = accounts_response;
            }
        }
    });
