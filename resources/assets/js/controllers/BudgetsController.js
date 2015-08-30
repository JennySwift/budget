(function () {

    angular
        .module('budgetApp')
        .controller('BudgetsController', budgets);

    function budgets ($scope, $http, BudgetsFactory, TagsFactory, FilterFactory, FeedbackFactory) {
        /**
         * scope properties
         */

        //$scope.totals = totals_response;
        $scope.basicTotals = basicTotals;
        $scope.fixedBudgets = fixedBudgets;
        $scope.fixedBudgetTotals = fixedBudgetTotals;
        $scope.flexBudgets = flexBudgets;
        $scope.feedbackFactory = FeedbackFactory;

        $scope.show.basic_totals = true;
        $scope.show.budget_totals = true;
        $scope.tab = 'fixed';

        /**
         * Watches
         */

        $scope.$watch('feedbackFactory.data', function (newValue, oldValue, scope) {
            if (newValue && newValue.message) {
                scope.provideFeedback(newValue.message);
            }
        });

        $scope.getTags = function () {
            TagsFactory.getTags()
                .then(function (response) {
                    $scope.tags = response.data;
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };

        $scope.updateTag = function ($tag, response) {
            var $index = _.indexOf($scope.tags, _.findWhere($scope.tags, {id: $tag.id}));
            $scope.tags[$index] = response.data.tag;
        };

        /**
         * Return true if tag has a budget already
         * @returns {boolean}
         */
        $scope.tagHasBudget = function ($new) {
            if ($new.flex_budget) {
                $scope.provideFeedback("You've got a flex budget for that tag.", 'error');
                return true;
            }
            else if ($new.fixed_budget) {
                $scope.provideFeedback("You've got a fixed budget for that tag.", 'error');
                return true;
            }
            return false;
        };

        /**
         * Clear the tag inputs and focus the correct input
         * after entering a new budget
         * todo: clear the budget input
         * @param $type
         */
        $scope.clearAndFocus = function ($type) {
            if ($type === 'fixed') {
                //I'm baffled as to why this works to clear the input when the ng-model is new_FB.
                //$scope.new_fixed_budget.tag.name = '';

                $("#new-fixed-budget-name-input").val("").focus();
                $("#new-fixed-budget-SD").val("");
                $("#new-fixed-budget-amount").val("");
            }
            else {
                $("#new-flex-budget-name-input").val("").focus();
                $("#new-flex-budget-SD").val("");
                $("#new-flex-budget-amount").val("");
            }
        };

        /**
         * For updating budget (amount, starting date) for an existing budget
         */
        $scope.updateBudget = function () {
            $scope.showLoading();
            $scope.budget_popup.sql_starting_date = $scope.formatDate($scope.budget_popup.formatted_starting_date);
            BudgetsFactory.update($scope.budget_popup, $scope.budget_popup.type)
                .then(function (response) {
                    $scope.handleUpdateResponse(response, 'Budget updated');
                    $scope.show.popups.budget = false;
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };

        $scope.handleUpdateResponse = function (response, $message) {
            FilterFactory.updateDataForControllers(response.data);
            $scope.totals.budget = response.data.totals.budget;
            $scope.hideLoading();
            $scope.provideFeedback($message);
        };

        /**
         * For creating a new budget for an existing tag
         * @param $keycode
         * @param $new
         * @param $type
         */
        $scope.createBudget = function ($keycode, $new, $type) {
            if ($keycode !== 13 || $scope.tagHasBudget($new)) {
                return;
            }

            $scope.showLoading();
            $new.sql_starting_date = $scope.formatDate($new.starting_date);
            BudgetsFactory.create($new, $type)
                .then(function (response) {
                    $scope.handleUpdateResponse(response, 'Budget created');
                    //$scope.updateTag($new, response);
                    $scope.clearAndFocus($type);
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };

        $scope.removeBudget = function ($tag) {
            if (confirm("Remove " + $tag.budget_type + " budget for " + $tag.name + "?")) {
                $scope.showLoading();
                BudgetsFactory.removeBudget($tag)
                    .then(function (response) {
                        FilterFactory.updateDataForControllers(response.data);
                        $scope.totals.budget = response.data.totals.budget;
                        $scope.updateTag($tag, response);
                        $scope.provideFeedback('Budget deleted');
                        $scope.hideLoading();
                    })
                    .catch(function (response) {
                        $scope.responseError(response);
                    });
            }
        };

        $scope.showBudgetPopup = function ($tag, $type) {
            $scope.budget_popup = $tag;
            $scope.budget_popup.type = $type;
            $scope.show.popups.budget = true;
        };

        //$scope.updateSavingsTotal = function ($keycode) {
        //    if ($keycode !== 13) {
        //        return;
        //    }
        //    savings.updatesavingsTotal()
        //        .then(function (response) {
        //            $scope.totals.basic.savings_total = response.data;
        //            $scope.show.savings_total.input = false;
        //            $scope.show.savings_total.edit_btn = true;
        //            $scope.getTotals();
        //        })
        //        .catch(function (response) {
        //            FeedbackFactory.provideFeedback('There was an error');
        //        });
        //};

        //$scope.addFixedToSavings = function ($keycode) {
        //    if ($keycode !== 13) {
        //        return;
        //    }
        //    savings.addFixedToSavings()
        //        .then(function (response) {
        //            $scope.totals.basic.savings_total = response.data;
        //            $scope.getTotals();
        //        })
        //        .catch(function (response) {
        //            FeedbackFactory.provideFeedback('There was an error');
        //        });
        //};

        //$scope.addPercentageToSavings = function ($keycode) {
        //    if ($keycode !== 13) {
        //        return;
        //    }
        //    savings.addPercentageToSavings()
        //        .then(function (response) {
        //            $scope.totals.basic.savings_total = response.data;
        //            $scope.getTotals();
        //        })
        //        .catch(function (response) {
        //            FeedbackFactory.provideFeedback('There was an error');
        //        });
        //};

    }

})();