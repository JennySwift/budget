angular.module('budgetApp')
    .directive('graphsDirective', function ($rootScope, FilterFactory) {
        return {
            scope: {

            },
            templateUrl: 'graphs-template',

            link: function ($scope) {

                $rootScope.$on('getGraphTotals', function () {
                    $rootScope.showLoading();
                    FilterFactory.getGraphTotals(FilterFactory.filter)
                        .then(function (response) {
                            $scope.graphFigures = FilterFactory.calculateGraphFigures(response.data);
                            $rootScope.hideLoading();
                        })
                        .catch(function (response) {
                            $rootScope.responseError(response);
                        })
                });

            }
        }
    });
