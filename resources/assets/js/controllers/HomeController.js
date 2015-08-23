(function () {

    angular
        .module('budgetApp')
        .controller('HomeController', home);

    function home ($scope, $http, BudgetsFactory, TransactionsFactory, PreferencesFactory, FeedbackFactory, ColorsFactory) {
        /**
         * scope properties
         */

        $scope.something = 'abcd123';

        //$scope.feedbackFactory = FeedbackFactory;
        $scope.transactionsFactory = TransactionsFactory;
        //$scope.feedback_messages = [];
        $scope.page = 'home';

        $scope.colors = colors_response;
        $scope.totals = totals_response;

        if ($scope.env === 'local') {
            $scope.tab = 'graphs';
        }
        else {
            $scope.tab = 'transactions';
        }



        /*=========show=========*/
        $scope.show = {
            actions: false,
            status: false,
            date: true,
            description: true,
            merchant: true,
            total: true,
            type: true,
            account: true,
            reconciled: true,
            tags: true,
            dlt: true,
            // modals
            color_picker: false,
            //components
            new_transaction: false,
            basic_totals: true,
            budget_totals: true,
            filter_totals: true,
            edit_transaction: false,
            edit_tag: false,
            budget: false,
            filter: false,
            autocomplete: {
                description: false,
                merchant: false
            },
            allocation_popup: false,
            new_transaction_allocation_popup: false,
            savings_total: {
                input: false,
                edit_btn: true
            }
        };

        /**
         * Watches
         */

        //$scope.$watch('feedbackFactory.data', function (newValue, oldValue, scope) {
        //    if (newValue && newValue.message) {
        //        scope.provideFeedback(newValue.message);
        //    }
        //});

        $scope.$watch('PreferencesFactory.date_format', function (newValue, oldValue) {
            if (!newValue) {
                return;
            }
            $scope.showLoading();
            PreferencesFactory.insertOrUpdateDateFormat(newValue)
                .then(function (response) {
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        });

        $scope.$watchCollection('colors', function (newValue) {
            $("#income-color-picker").val(newValue.income);
            $("#expense-color-picker").val(newValue.expense);
            $("#transfer-color-picker").val(newValue.transfer);
        });

        /**
         * End watches
         */

        /**
         * For trying to get the page load faster,
         * seeing the queries that are taking place
         */
        $scope.debugTotals = function () {
            return $http.get('/test');
        };

        $scope.updateColors = function () {
            $scope.showLoading();
            ColorsFactory.updateColors($scope.colors)
                .then(function (response) {
                    //Todo: return the colors in the response to update them
                    $scope.show.color_picker = false;
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };

        $scope.defaultColor = function ($type, $default_color) {
            if ($type === 'income') {
                $scope.colors.income = $default_color;
            }
            else if ($type === 'expense') {
                $scope.colors.expense = $default_color;
            }
            else if ($type === 'transfer') {
                $scope.colors.transfer = $default_color;
            }
        };

        // =================================allocation=================================

        $scope.showAllocationPopup = function ($transaction) {
            $scope.show.allocation_popup = true;
            $scope.allocation_popup = $transaction;

            $scope.showLoading();
            BudgetsFactory.getAllocationTotals($transaction.id)
                .then(function (response) {
                    $scope.allocation_popup.allocation_totals = response.data;
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };

        $scope.updateChart = function () {
            $(".bar_chart_li:first-child").css('height', '0%');
            $(".bar_chart_li:nth-child(2)").css('height', '0%');
            $(".bar_chart_li:first-child").css('height', getTotal()[6] + '%');
            $(".bar_chart_li:nth-child(2)").css('height', getTotal()[5] + '%');
        };

        $scope.toggleFilter = function () {
            $scope.show.filter = !$scope.show.filter;
        };

        $scope.transactionsTab = function () {
            $scope.tab = 'transactions';
            $scope.show.basic_totals = true;
            $scope.show.budget_totals = true;
            $scope.show.filter = false;
        };

        $scope.graphsTab = function () {
            $scope.tab = 'graphs';
            $scope.show.basic_totals = false;
            $scope.show.budget_totals = false;
            $scope.show.filter = true;
        };

        if ($scope.tab === 'graphs') {
            $scope.graphsTab();
        }










        /*==============================quick select of transactions==============================*/

        $("body").on('click', '.mass-delete-checkbox-container', function (event) {
            var $this = $(this).closest("tbody");
            var $checked = $(".checked");
            $(".last-checked").removeClass("last-checked");
            $(".first-checked").removeClass("first-checked");

            if (event.shiftKey) {
                var $last_checked = $($checked).last().closest("tbody");
                var $first_checked = $($checked).first().closest("tbody");

                $($last_checked).addClass("last-checked");
                $($first_checked).addClass("first-checked");
                $($this).addClass("checked");

                if ($($this).prevAll(".last-checked").length !== 0) {
                    //$this is after .last-checked
                    shiftSelect("forwards");
                }
                else if ($($this).nextAll(".last-checked").length !== 0) {
                    //$this is before .last-checked
                    shiftSelect("backwards");
                }
            }
            else if (event.altKey) {
                $($this).toggleClass('checked');
            }
            else {
                console.log("no shift");
                $(".checked").not($this).removeClass('checked');
                $($this).toggleClass('checked');
            }
        });

        function shiftSelect ($direction) {
            $("#my_results tbody").each(function () {
                var $prev_checked_length = $(this).prevAll(".checked").length;
                var $after_checked_length = $(this).nextAll(".checked").length;
                var $after_last_checked = $(this).prevAll(".last-checked").length;
                var $before_first_checked = $(this).nextAll(".first-checked").length;

                if ($direction === "forwards") {
                    //if it's after $last_checked and before $this
                    if ($prev_checked_length !== 0 && $after_checked_length !== 0 && $after_last_checked !== 0) {
                        $(this).addClass('checked');
                    }
                }
                else if ($direction === "backwards") {
                    if ($prev_checked_length !== 0 && $after_checked_length !== 0 && $before_first_checked !== 0) {
                        $(this).addClass('checked');
                    }
                }
            });
        }

    }

})();