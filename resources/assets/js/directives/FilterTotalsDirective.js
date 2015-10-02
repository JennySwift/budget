angular.module('budgetApp')
    .directive('filterTotalsDirective', function ($rootScope, FilterFactory) {
        return {
            scope: {
                //filterTotals: '=filtertotals',
                show: '=show',
                filter: '=filter'
            },
            templateUrl: 'filter-totals-template',

            link: function ($scope) {

                $scope.filterTotals = filterBasicTotals;

                $rootScope.$on('getFilterBasicTotals', function () {
                    $rootScope.showLoading();
                    FilterFactory.getBasicTotals($scope.filter)
                        .then(function (response) {
                            $scope.filterTotals = response.data;
                            $rootScope.hideLoading();
                        })
                        .catch(function (response) {
                            $rootScope.responseError(response);
                        })
                });

            }
        }
    });
