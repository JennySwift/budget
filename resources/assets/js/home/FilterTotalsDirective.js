angular.module('budgetApp')
    .directive('filterTotalsDirective', function ($rootScope, FilterFactory) {
        return {
            scope: {
                show: '=show',
                filter: '=filter'
            },
            templateUrl: 'filter-totals-template',

            link: function ($scope) {

                $scope.filterTotals = FilterFactory.filterBasicTotals;

                $rootScope.$on('getFilterBasicTotals', function () {
                    $rootScope.showLoading();
                    FilterFactory.getBasicTotals($scope.filter)
                        .then(function (response) {
                            FilterFactory.filterBasicTotals = response.data;
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
