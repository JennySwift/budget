(function () {

    angular
        .module('budgetApp')
        .controller('FilterController', filter);

    function filter ($scope, FilterFactory) {

        $scope.filterFactory = FilterFactory;
        $scope.types = ["income", "expense", "transfer"];
        $scope.filterTab = 'show';
        $scope.accounts = accounts_response;

        /**
         * Watches
         */

        $scope.$watchCollection('filter.budgets.in.and', function (newValue, oldValue) {
            if (newValue === oldValue) {
                return;
            }
            $scope.runFilter();
        });

        $scope.$watchCollection('filter.budgets.in.or', function (newValue, oldValue) {
            if (newValue === oldValue) {
                return;
            }
            $scope.runFilter();
        });

        $scope.$watchCollection('filter.budgets.out', function (newValue, oldValue) {
            if (newValue === oldValue) {
                return;
            }
            $scope.runFilter();
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
                $scope.runFilter();
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
            $scope.runFilter();
        };

        $scope.nextResults = function () {
            if ($scope.filter.offset + ($scope.filter.num_to_fetch * 1) > $scope.filterTotals.numTransactions) {
                //stop it going past the end.
                return;
            }

            $scope.filter.offset+= ($scope.filter.num_to_fetch * 1);
            updateRange();
            $scope.runFilter();
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
            $scope.runFilter(true);
        };

        $scope.filterDate = function ($keycode) {
            if ($keycode !== 13) {
                return false;
            }
            $scope.runFilter();
        };

        $scope.filterTotal = function ($keycode) {
            if ($keycode !== 13) {
                return false;
            }
            $scope.runFilter();
        };

        /**
         * $type is either 'in' or 'out'
         * @param $field
         * @param $type
         */
        $scope.clearFilterField = function ($field, $type) {
            $scope.filter[$field][$type] = "";
            $scope.runFilter();
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
            $scope.runFilter();
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