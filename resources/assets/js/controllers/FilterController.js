(function () {

    angular
        .module('budgetApp')
        .controller('FilterController', filter);

    function filter ($rootScope, $scope, FilterFactory) {

        $scope.types = ["income", "expense", "transfer"];
        $scope.filterTab = 'show';
        $scope.accounts = accounts_response;

        $scope.filter = FilterFactory.filter;
        $scope.filterTotals = filterBasicTotals;
        $scope.test = FilterFactory.test;

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

        /**
         * I have three instances of FilterController and when this is called
         * from one of them, the wrong $scope.filter is updated.
         */
        //$scope.resetFilter = function () {
        //    FilterFactory.updateTest(2);
        //    $scope.filter = FilterFactory.resetFilter();
        //    $rootScope.$emit('runFilter');
        //};

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

        /**
         * Watches
         */

        $scope.$watchCollection('filter.budgets.in.and', function (newValue, oldValue) {
            if (newValue === oldValue) {
                return;
            }
            $rootScope.$emit('runFilter');
        });

        $scope.$watchCollection('filter.budgets.in.or', function (newValue, oldValue) {
            if (newValue === oldValue) {
                return;
            }
            $rootScope.$emit('runFilter');
        });

        $scope.$watchCollection('filter.budgets.out', function (newValue, oldValue) {
            if (newValue === oldValue) {
                return;
            }
            $rootScope.$emit('runFilter');
        });

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

        $scope.resetSearch = function () {
            $("#search-type-select, #search-account-select, #search-reconciled-select").val("all");
            $("#single-date-input, #from-date-input, #to-date-input, #search-descriptions-input, #search-merchants-input, #search-tags-input").val("");
            $("#search-tag-location").html("");
            $scope.filter(true);
        };

        $scope.filterDescriptionOrMerchant = function ($keycode) {
            if ($keycode !== 13) {
                return false;
            }
            $scope.resetOffset();
            $rootScope.$emit('runFilter');
        };

        $scope.filterDate = function ($keycode) {
            if ($keycode !== 13) {
                return false;
            }
            $rootScope.$emit('runFilter');
        };

        $scope.filterTotal = function ($keycode) {
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
        $scope.clearFilterField = function ($field, $type) {
            $scope.filter[$field][$type] = "";
            $rootScope.$emit('runFilter');
        };

        /**
         * $type1 is 'in' or 'out'.
         * $type2 is 'and' or 'or'.
         * @param $type1
         * @param $type2
         */
        $scope.clearTagField = function ($type1, $type2) {
            if ($type2) {
                $scope.filter.budgets[$type1][$type2] = [];
            }
            else {
                $scope.filter.budgets[$type1] = [];
            }
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

        $scope.resetOffset = function () {
            $scope.filter.offset = 0;
        };

        $scope.showContent = function (event) {
            $(event.target).next().addClass('show-me').removeClass('hide');
        };

        $scope.hideContent = function (event) {
            $(event.target).next().addClass('hide-me').removeClass('show');
        };

    }

})();