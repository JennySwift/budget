(function () {

    angular
        .module('budgetApp')
        .controller('FilterController', filter);

    function filter ($scope, $http, FilterFactory, FeedbackFactory) {
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

        //console.log($scope.loading);

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
            }
        });

        $scope.$watch('filterFactory.filter_results.totals', function (newValue, oldValue, scope) {
            if (newValue) {
                scope.totals = newValue;
            }
        });

        $scope.$watchCollection('filter.tags', function (newValue, oldValue) {
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

        $scope.responseError = function (response) {
            if (response.status === 503) {
                FeedbackFactory.provideFeedback('Sorry, application under construction. Please try again later.');
            }
            else {
                FeedbackFactory.provideFeedback('There was an error');
            }
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
                    $scope.hideLoading();
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

        //$(".clear-search-button").on('click', function () {
        //    if ($(this).attr('id') == "clear-tags-btn") {
        //        $search_tag_array.length = 0;
        //        $("#search-tag-location").html($search_tag_array);
        //    }
        //    $(this).closest(".input-group").children("input").val("");
        //    $scope.multiSearch(true);
        //});

        //$("#search-div").on('click', '#search-tag-location li', function () {
        //    removeTag(this, $search_tag_array, $("#search-tag-location"), multiSearch);
        //});

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
         * Needed for filter
         * @param $keycode
         * @param $func
         * @param $params
         */
        //$scope.checkKeycode = function ($keycode, $func, $params) {
        //    if ($keycode === 13) {
        //        $func($params);
        //    }
        //};

        $scope.clearFilterField = function ($field) {
            if ($field === 'tags') {
                $scope.filter.tags = [];
            }
            else {
                $scope.filter[$field] = "";
                $scope.multiSearch();
            }
        };

        $scope.resetOffset = function () {
            $scope.filter.offset = 0;
        };

        $scope.showContent = function (event) {
            $(event.target).next().addClass('show-me').removeClass('hide');
            //$(event.target).next().addClass('animated slideInDown').removeClass('hide');
        };

        $scope.hideContent = function (event) {
            $(event.target).next().addClass('hide-me').removeClass('show');
            //$(event.target).next().addClass('animated slideOutUp').removeClass('show');
        };


    }

})();