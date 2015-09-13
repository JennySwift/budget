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
        $scope.budgets = budgets;
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

                if (newValue !== oldValue) {
                    $scope.filterTransactions();
                }
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

        $scope.$watchCollection('filter.budgets.in.and', function (newValue, oldValue) {
            if (newValue === oldValue) {
                return;
            }
            $scope.filterTransactions();
        });

        $scope.$watchCollection('filter.budgets.in.or', function (newValue, oldValue) {
            if (newValue === oldValue) {
                return;
            }
            $scope.filterTransactions();
        });

        $scope.$watchCollection('filter.budgets.out', function (newValue, oldValue) {
            if (newValue === oldValue) {
                return;
            }
            $scope.filterTransactions();
        });

        $scope.$watchGroup(['filter.offset', 'filter.num_to_fetch'], function (newValue, oldValue) {
            $scope.filter.display_from = $scope.filter.offset + 1;
            $scope.filter.display_to = $scope.filter.offset + ($scope.filter.num_to_fetch * 1);
            if (newValue === oldValue) {
                return;
            }
            $scope.filterTransactions();
        });

        /**
         * End watches
         */

        $scope.calculateGraphFigures = function () {
            $scope.graphFigures = {
                months: []
            };
            //console.log($scope.graph_totals);

            $($scope.graph_totals.monthsTotals).each(function () {
                var $income = this.income.raw;
                var $expenses = this.expenses.raw * -1;

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

        $scope.filterTransactions = function () {
            $scope.showLoading();
            FilterFactory.filterTransactions($scope.filter)
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
            if ($scope.filter.offset + ($scope.filter.num_to_fetch * 1) > $scope.totals.numTransactions) {
                //stop it going past the end.
                return;
            }
            $scope.filter.offset+= ($scope.filter.num_to_fetch * 1);
        };

        $scope.resetSearch = function () {
            $("#search-type-select, #search-account-select, #search-reconciled-select").val("all");
            $("#single-date-input, #from-date-input, #to-date-input, #search-descriptions-input, #search-merchants-input, #search-tags-input").val("");
            $("#search-tag-location").html("");
            $scope.filterTransactions(true);
        };

        $scope.filterDescriptionOrMerchant = function ($keycode) {
            if ($keycode !== 13) {
                return false;
            }
            $scope.resetOffset();
            $scope.filterTransactions(true);
        };

        $scope.filterDate = function ($keycode) {
            if ($keycode !== 13) {
                return false;
            }
            $scope.filterTransactions();
        };

        $scope.filterTotal = function ($keycode) {
            if ($keycode !== 13) {
                return false;
            }
            $scope.filterTransactions();
        };

        /**
         * $type is either 'in' or 'out'
         * @param $field
         * @param $type
         */
        $scope.clearFilterField = function ($field, $type) {
            $scope.filter[$field][$type] = "";
            $scope.filterTransactions();
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
            $scope.filterTransactions();
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