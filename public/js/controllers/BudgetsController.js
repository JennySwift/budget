(function () {

    angular
        .module('budgetApp')
        .controller('budgets', budgets);

    function budgets ($scope, $http, BudgetsFactory, TagsFactory, FilterFactory, FeedbackFactory) {
        /**
         * scope properties
         */

        $scope.me = me;
        $scope.totals = totals_response;
        $scope.feedback_messages = [];
        $scope.tags = tags_response;
        $scope.feedbackFactory = FeedbackFactory;

        $scope.show = {
            basic_totals: true,
            budget_totals: true,
            popups: {}
        };


        /**
         * Watches
         */

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

        $scope.getTags = function () {
            TagsFactory.getTags()
                .then(function (response) {
                    $scope.tags = response.data;
                })
                .catch(function (response) {
                    FeedbackFactory.provideFeedback('There was an error');
                });
        };

        $scope.updateBudget = function ($keycode, $new, $type) {
            if ($keycode !== 13 || $scope.tagHasBudget($new)) {
                return;
            }
            BudgetsFactory.updateBudget($new, $type + '_budget', $new.budget)
                .then(function (response) {
                    FilterFactory.updateDataForControllers(response.data);
                    $scope.totals.budget = response.data.totals.budget;
                    $scope.updateTag($new, response);
                    $scope.clearAndFocus($type);
                })
                .catch(function (response) {
                    FeedbackFactory.provideFeedback('There was an error');
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
                FeedbackFactory.provideFeedback("You've got a flex budget for that tag.");
                return true;
            }
            else if ($new.fixed_budget) {
                FeedbackFactory.provideFeedback("You've got a fixed budget for that tag.");
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
            }
            else {
                $("#new-flex-budget-name-input").val("").focus();
            }
        };

        $scope.removeBudget = function ($tag) {
            if (confirm("Remove " + $tag.budget_type + " budget for " + $tag.name + "?")) {
                BudgetsFactory.updateBudget($tag, $tag.budget_type + '_budget', 'NULL')
                    .then(function (response) {
                        FilterFactory.updateDataForControllers(response.data);
                        $scope.totals.budget = response.data.totals.budget;
                        $scope.updateTag($tag, response);
                    })
                    .catch(function (response) {
                        FeedbackFactory.provideFeedback('There was an error');
                    });
            }
        };

        $scope.updateCSDSetup = function ($tag) {
            $scope.edit_CSD = $tag;
            $scope.show.popups.edit_CSD = true;
        };

        $scope.updateCSD = function () {
            BudgetsFactory.updateCSD($scope.edit_CSD)
                .then(function (response) {
                    FilterFactory.updateDataForControllers(response.data);
                    $scope.totals.budget = response.data.budget;
                    $scope.show.popups.edit_CSD = false;
                })
                .catch(function (response) {
                    FeedbackFactory.provideFeedback('There was an error');
                });
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

        $scope.closePopup = function ($event, $popup) {
            var $target = $event.target;
            if ($target.className === 'popup-outer') {
                $scope.show.popups[$popup] = false;
            }
        };
    }

})();