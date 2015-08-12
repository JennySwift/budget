(function () {

    angular
        .module('budgetApp')
        .controller('FilterController', filter);

    function filter ($scope, $http, FilterFactory, FeedbackFactory) {

        $scope.something = 'abcdefghijklmn';
        /**
         * scope properties
         */

        $scope.filterFactory = FilterFactory;
        $scope.accounts = accounts_response;
        $scope.tags = tags_response;
        $scope.types = ["income", "expense", "transfer"];
        $scope.totals = filter_response.totals;
        $scope.filterTab = 'show';
        //$scope.loading = true;

        $scope.resetFilter = function () {
            FilterFactory.resetFilter();
        };

        /**
         * Watches
         */

        // Not sure why I have to do this in the filter controller,
        // but $scope.filter wasn't updating otherwise
        $scope.$watch('filterFactory.filter', function (newValue, oldValue, scope) {
            if (newValue) {
                scope.filter = newValue;
                $scope.multiSearch();
            }
        });

        $scope.$watch('filterFactory.filter_results.totals', function (newValue, oldValue, scope) {
            if (newValue) {
                scope.totals = newValue;
                //$scope.calculateGraphFigures();
            }
        });

        $scope.$watch('filterFactory.filter_results.graph_totals', function (newValue, oldValue, scope) {
            if (newValue) {
                //This is running many times when it shouldn't
                scope.graph_totals = newValue;
                $scope.calculateGraphFigures();
            }
        });

        $scope.$watchCollection('filter.tags.in.and', function (newValue, oldValue) {
            if (newValue === oldValue) {
                return;
            }
            $scope.multiSearch(true);
        });

        $scope.$watchCollection('filter.tags.in.or', function (newValue, oldValue) {
            if (newValue === oldValue) {
                return;
            }
            $scope.multiSearch(true);
        });

        $scope.$watchCollection('filter.tags.out', function (newValue, oldValue) {
            if (newValue === oldValue) {
                return;
            }
            $scope.multiSearch(true);
        });

        $scope.$watchGroup(['filter.offset', 'filter.num_to_fetch'], function (newValue, oldValue) {
            $scope.filter.display_from = $scope.filter.offset + 1;
            $scope.filter.display_to = $scope.filter.offset + ($scope.filter.num_to_fetch * 1);
            if (newValue === oldValue) {
                return;
            }
            $scope.multiSearch(true);
        });

        /**
         * End watches
         */

        $scope.calculateGraphFigures = function () {
            $scope.graphFigures = {
                months: []
            };
            console.log($scope.graph_totals);

            $($scope.graph_totals.monthsTotals).each(function () {
                var $income = this.income;
                var $expenses = this.expenses * -1;

                //var $max = Math.max($income, $expenses);
                var $max = $scope.graph_totals.maxTotal;
                var $num = 500 / $max;

                $scope.graphFigures.months.push({
                    incomeHeight: $income * $num,
                    expensesHeight: $expenses * $num,
                    income: this.income,
                    expenses: this.expenses,
                    month: this.month
                });
            });
        };

        $scope.multiSearch = function () {
            $scope.showLoading();
            FilterFactory.multiSearch($scope.filter)
                .then(function (response) {
                    FilterFactory.updateDataForControllers({filter_results: response.data});
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                })
        };

        //Todo: I might not need some of this code (not allowing offset to be less than 0)
        // todo: since I disabled the button if that is the case
        $scope.prevResults = function () {
            //make it so the offset cannot be less than 0.
            if ($scope.filter.offset - $scope.filter.num_to_fetch < 0) {
                $scope.filter.offset = 0;
            }
            else {
                $scope.filter.offset-= ($scope.filter.num_to_fetch * 1);
            }
        };

        $scope.nextResults = function () {
            if ($scope.filter.offset + ($scope.filter.num_to_fetch * 1) > $scope.totals.num_transactions) {
                //stop it going past the end.
                return;
            }
            $scope.filter.offset+= ($scope.filter.num_to_fetch * 1);
        };

        $scope.resetSearch = function () {
            $("#search-type-select, #search-account-select, #search-reconciled-select").val("all");
            $("#single-date-input, #from-date-input, #to-date-input, #search-descriptions-input, #search-merchants-input, #search-tags-input").val("");
            $("#search-tag-location").html("");
            $scope.multiSearch(true);
        };

        $scope.filterDescriptionOrMerchant = function ($keycode) {
            if ($keycode !== 13) {
                return false;
            }
            $scope.resetOffset();
            $scope.multiSearch(true);
        };

        $scope.filterDate = function ($keycode) {
            if ($keycode !== 13) {
                return false;
            }
            $scope.multiSearch();
        };

        $scope.filterTotal = function ($keycode) {
            if ($keycode !== 13) {
                return false;
            }
            $scope.multiSearch();
        };

        /**
         * $type is either 'in' or 'out'
         * @param $field
         * @param $type
         */
        $scope.clearFilterField = function ($field, $type) {
            $scope.filter[$field][$type] = "";
            $scope.multiSearch();
        };

        /**
         * $type1 is 'in' or 'out'.
         * $type2 is 'and' or 'or'.
         * @param $type1
         * @param $type2
         */
        $scope.clearTagField = function ($type1, $type2) {
            if ($type2) {
                $scope.filter.tags[$type1][$type2] = [];
            }
            else {
                $scope.filter.tags[$type1] = [];
            }
        };

        /**
         * $type is either 'in' or 'out'
         * @param $field
         * @param $type
         */
        $scope.clearDateField = function ($field, $type) {
            $scope.filter[$field][$type]['user'] = "";
            $scope.multiSearch();
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