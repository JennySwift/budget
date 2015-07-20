(function () {

    angular
        .module('budgetApp')
        .controller('HomeController', home);

    function home ($scope, $http, BudgetsFactory, TransactionsFactory, PreferencesFactory, FeedbackFactory) {
        /**
         * scope properties
         */

        $scope.feedbackFactory = FeedbackFactory;

        $scope.feedback_messages = [];

        $scope.page = 'home';

        $scope.colors = colors_response;

        $scope.totals = totals_response;

        $scope.me = me;

        //$scope.transactions_limited = []; //limited to 30 results

        /*=========other=========*/
        $scope.allocation_popup_transaction = {};
        $scope.selected = {}; //for getting the selected tag in autocomplete
        $scope.typing = {
            new_transaction: {
                tag: ''
            }
        };
        //$scope.autocomplete = {
        //    transactions: {},
        //    tags: {}
        //};

        $scope.messages = {};
        $scope.tag_input = ""; //for the inputs where the tag is autocompleted

        /*=========selected=========*/

        $scope.selected = {};

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
            edit_CSD: false,
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

        $scope.$watch('feedbackFactory.data', function (newValue, oldValue, scope) {
            if (newValue && newValue.message) {
                scope.provideFeedback(newValue.message);
            }
        });

        $scope.provideFeedback = function ($message) {
            $scope.feedback_messages.push($message);
            setTimeout(function () {
                $scope.feedback_messages = _.without($scope.feedback_messages, $message);
                $scope.$apply();
            }, 3000);
        };

        $scope.testFeedback = function () {
            $scope.provideFeedback('something');
        };

        /**
         * select
         */

        $scope.transactionsFactory = TransactionsFactory;

        //$scope.testControllers = function () {
        //    TransactionsFactory.testControllers(10);
        //};
        //
        //$scope.num = TransactionsFactory.testControllers(5);
        //
        //$scope.$watch('transactionsFactory.testControllers()', function (newValue, oldValue, scope) {
        //    scope.num = newValue;
        //});

        /**
         * update
         */

        $scope.updateColors = function () {
            settings.updateColors($scope.colors)
                .then(function (response) {
                    $scope.getColors();
                    $scope.show.color_picker = false;
                })
                .catch(function (response) {
                    $scope.provideFeedback('There was an error');
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

            BudgetsFactory.getAllocationTotals($transaction.id)
                .then(function (response) {
                    $scope.allocation_popup.allocation_totals = response.data;
                })
                .catch(function (response) {
                    $scope.provideFeedback('There was an error');
                });
        };

        /**
         * watches
         */

        $scope.$watch('PreferencesFactory.date_format', function (newValue, oldValue) {
            if (!newValue) {
                return;
            }
            PreferencesFactory.insertOrUpdateDateFormat(newValue).then(function (response) {
                // $scope. = response.data;
            });
        });

        $scope.$watchCollection('colors', function (newValue) {
            $("#income-color-picker").val(newValue.income);
            $("#expense-color-picker").val(newValue.expense);
            $("#transfer-color-picker").val(newValue.transfer);
        });



        /**
         * other
         */

        $scope.getColors = function () {
            settings.getColors()
                .then(function (response) {
                    $scope.colors = response.data;
                })
                .catch(function (response) {
                    $scope.provideFeedback('There was an error');
                });
        };

        $scope.updateChart = function () {
            $(".bar_chart_li:first-child").css('height', '0%');
            $(".bar_chart_li:nth-child(2)").css('height', '0%');
            $(".bar_chart_li:first-child").css('height', getTotal()[6] + '%');
            $(".bar_chart_li:nth-child(2)").css('height', getTotal()[5] + '%');
        };



        /**
         * show
         */

        $scope.showSavingsTotalInput = function () {
            $scope.show.savings_total.input = true;
            $scope.show.savings_total.edit_btn = false;
        };

        $scope.toggleFilter = function () {
            $scope.show.filter = !$scope.show.filter;
        };

        $scope.closePopup = function ($event, $popup) {
            var $target = $event.target;
            if ($target.className === 'popup-outer') {
                $scope.show.popups[$popup] = false;
            }
        };
    }

})();