(function () {

    angular
        .module('budgetApp')
        .controller('FilterController', filter);

    function filter ($rootScope, $scope, FilterFactory) {

        $scope.types = ["income", "expense", "transfer"];
        $scope.filterTab = 'show';

        $scope.filter = FilterFactory.filter;
        $scope.filterTotals = filterBasicTotals;

        $scope.runFilter = function () {
            $rootScope.$emit('runFilter');
        };

        $rootScope.$on('runFilter', function (event, data) {
            $scope.getFilterBasicTotals();
            if ($scope.tab === 'transactions') {
                $scope.$emit('filterTransactions', $scope.filter);
            }
            else {
                $scope.getGraphTotals();
            }
        });

        $scope.getFilterBasicTotals = function () {
            FilterFactory.getBasicTotals($scope.filter)
                .then(function (response) {
                    $scope.filterTotals = response.data;
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                })
        };

        $rootScope.$on('resetFilter', function (event, data) {
            $scope.filter = FilterFactory.resetFilter();
            $rootScope.$emit('runFilter');
        });

        $scope.resetFilter = function () {
            $scope.$emit('resetFilter');
        };

        $scope.getGraphTotals = function () {
            FilterFactory.getGraphTotals($scope.filter)
                .then(function (response) {
                    $scope.graphTotals = response.data;
                    calculateGraphFigures();
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                })
        };

        function calculateGraphFigures () {
            $scope.graphFigures = FilterFactory.calculateGraphFigures($scope.graphTotals);
        }

        //Todo: I might not need some of this code (not allowing offset to be less than 0)
        // todo: since I disabled the button if that is the case
        $scope.prevResults = function () {
            //make it so the offset cannot be less than 0.
            if ($scope.filter.offset - $scope.filter.num_to_fetch < 0) {
                $scope.filter.offset = 0;
            }
            else {
                $scope.filter.offset-= ($scope.filter.num_to_fetch * 1);
                updateRange();
                $rootScope.$emit('runFilter');
            }
        };

        /**
         * Updates filter.display_from and filter.display_to values
         */
        function updateRange () {
            $scope.filter.display_from = $scope.filter.offset + 1;
            $scope.filter.display_to = $scope.filter.offset + ($scope.filter.num_to_fetch * 1);
        }

        $scope.changeNumToFetch = function () {
            updateRange();
            $rootScope.$emit('runFilter');
        };

        $scope.nextResults = function () {
            if ($scope.filter.offset + ($scope.filter.num_to_fetch * 1) > $scope.filterTotals.numTransactions) {
                //stop it going past the end.
                return;
            }

            $scope.filter.offset+= ($scope.filter.num_to_fetch * 1);
            updateRange();
            $rootScope.$emit('runFilter');
        };

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

    }

})();