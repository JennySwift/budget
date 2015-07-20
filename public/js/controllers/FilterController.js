(function () {

    angular
        .module('budgetApp')
        .controller('FilterController', filter);

    function filter ($scope, $http, FilterFactory) {
        /**
         * scope properties
         */

        $scope.filterFactory = FilterFactory;

        $scope.accounts = accounts_response;
        $scope.tags = tags_response;

        $scope.types = ["income", "expense", "transfer"];

        $scope.totals = filter_response.totals;

        $scope.resetFilter = function () {
            $scope.filter = {
                budget: "all",
                total: "",
                types: [],
                accounts: [],
                single_date: "",
                from_date: "",
                to_date: "",
                description: "",
                merchant: "",
                tags: [],
                reconciled: "any",
                offset: 0,
                num_to_fetch: 20
            };
        };

        $scope.resetFilter();

        /**
         * Watches
         */

        // Not sure why I have to do this in the filter controller,
        // but $scope.filter wasn't updating otherwise
        $scope.$watch('filterFactory.filter', function (newValue, oldValue, scope) {
            if (newValue) {
                scope.filter = newValue;
            }
        });

        $scope.$watch('filterFactory.filter_results.totals', function (newValue, oldValue, scope) {
            if (newValue) {
                scope.totals = newValue;
            }
        });

        $scope.multiSearch = function () {
            FilterFactory.multiSearch($scope.filter)
                .then(function (response) {
                    FilterFactory.updateDataForControllers({filter_results: response.data});
                })
                .catch(function (response) {
                    $scope.provideFeedback('There was an error');
                })
        };

        //todo: This stuff should go elsewhere
        //if ($new_transaction && $scope.new_transaction.multiple_budgets) {
        //    //multiSearch has been called after entering a new transaction.
        //    //The new transaction has multiple budgets.
        //    //Find the transaction that was just entered in $scope.TransactionsFactory.
        //    //This is so that the transaction is updated live when actions are done in the allocation popup. Otherwise it will need a page refresh.
        //    $transaction = _.find($scope.transactions, function ($scope_transaction) {
        //        return $scope_transaction.id === $scope.allocation_popup_transaction.id;
        //    });
        //
        //    if ($transaction) {
        //        $scope.showAllocationPopup($transaction);
        //    }
        //    else {
        //        //the transaction isn't showing with the current filter settings
        //        $scope.showAllocationPopup($scope.allocation_popup_transaction);
        //    }
        //}

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

        $(".clear-search-button").on('click', function () {
            if ($(this).attr('id') == "clear-tags-btn") {
                $search_tag_array.length = 0;
                $("#search-tag-location").html($search_tag_array);
            }
            $(this).closest(".input-group").children("input").val("");
            $scope.multiSearch(true);
        });

        $("#search-div").on('click', '#search-tag-location li', function () {
            removeTag(this, $search_tag_array, $("#search-tag-location"), multiSearch);
        });

        $scope.$watchCollection('filter.accounts', function (newValue, oldValue) {
            if (newValue === oldValue) {
                return;
            }
            $scope.multiSearch(true);
        });

        $scope.$watchCollection('filter.types', function (newValue, oldValue) {
            if (newValue === oldValue) {
                return;
            }
            $scope.multiSearch(true);
        });

        $scope.$watch('filter.description', function (newValue, oldValue) {
            if (newValue === oldValue) {
                return;
            }
            $scope.resetOffset();
            $scope.multiSearch(true);
        });

        $scope.$watch('filter.merchant', function (newValue, oldValue) {
            if (newValue === oldValue) {
                return;
            }
            $scope.resetOffset();
            $scope.multiSearch(true);
        });

        $scope.$watchCollection('filter.tags', function (newValue, oldValue) {
            if (newValue === oldValue) {
                return;
            }
            $scope.multiSearch(true);
        });

        $scope.$watch('filter.single_date', function (newValue, oldValue) {
            if (newValue === oldValue) {
                return;
            }
            if (newValue === "") {
                $scope.filter.single_date_sql = "";
                $scope.multiSearch(true);
            }
        });

        $scope.$watch('filter.from_date', function (newValue, oldValue) {
            if (newValue === oldValue) {
                return;
            }
            if (newValue === "") {
                $scope.filter.from_date_sql = "";
                $scope.multiSearch(true);
            }
        });

        $scope.$watch('filter.to_date', function (newValue, oldValue) {
            if (newValue === oldValue) {
                return;
            }
            if (newValue === "") {
                $scope.filter.to_date_sql = "";
                $scope.multiSearch(true);
            }
        });

        $scope.$watch('filter.total', function (newValue, oldValue) {
            if (newValue === oldValue) {
                return;
            }
            $scope.multiSearch(true);
        });

        $scope.$watch('filter.reconciled', function (newValue, oldValue) {
            if (newValue === oldValue) {
                return;
            }
            $scope.multiSearch(true);
        });

        $scope.$watch('filter.budget', function (newValue, oldValue) {
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
         * Needed for filter
         * @param $keycode
         * @param $func
         * @param $params
         */
        $scope.checkKeycode = function ($keycode, $func, $params) {
            if ($keycode === 13) {
                $func($params);
            }
        };

        $scope.clearFilterField = function ($field) {
            if ($field === 'tags') {
                $scope.filter.tags = [];
            }
            else {
                $scope.filter[$field] = "";
            }
        };

        $scope.resetOffset = function () {
            $scope.filter.offset = 0;
        };


    }

})();