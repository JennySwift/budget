angular.module('budgetApp')
    .directive('filterToolbarDirective', function ($rootScope, FilterFactory) {
        return {
            scope: {

            },
            templateUrl: 'filter-toolbar-template',

            link: function ($scope) {
                $scope.filter = FilterFactory.filter;
                $scope.filterFactory = FilterFactory;

                $scope.$watch('filterFactory.filterBasicTotals', function (newValue, oldValue, scope) {
                    $scope.filterTotals = newValue;
                });

                $rootScope.$on('setFilterInToolbarDirective', function () {
                    $scope.filter = FilterFactory.filter;
                });

                $scope.resetFilter = function () {
                    FilterFactory.resetFilter();
                    $scope.filter = FilterFactory.filter;
                    $rootScope.$emit('runFilter');
                };

                $scope.changeNumToFetch = function () {
                    FilterFactory.updateRange($scope.filter.num_to_fetch);
                    $rootScope.$emit('runFilter');
                };

                $scope.prevResults = function () {
                    FilterFactory.prevResults();
                };

                $scope.nextResults = function () {
                    FilterFactory.nextResults($scope.filterTotals.numTransactions);
                };

                $scope.saveFilter = function () {
                    var $name = prompt('Please name your filter');
                    $rootScope.showLoading();
                    FilterFactory.saveFilter($name)
                        .then(function (response) {
                            $rootScope.$emit('newSavedFilter', response.data.data);
                            $rootScope.$broadcast('provideFeedback', 'Filter saved');
                            $rootScope.hideLoading();
                        })
                        .catch(function (response) {
                            $rootScope.responseError(response);
                        });
                };
            }
        }
    });
