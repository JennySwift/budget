(function () {

    angular
        .module('budgetApp')
        .controller('BudgetsController', budgets);

    function budgets ($scope, $http, BudgetsFactory, FilterFactory, FeedbackFactory) {
        /**
         * scope properties
         */

        $scope.show = {
            newBudget: false
        };

        $scope.toggleNewBudget = function () {
            $scope.show.newBudget = true;
        };
        //$scope.fixedBudgets = fixedBudgets;
        //$scope.flexBudgets = flexBudgets;
        //$scope.feedbackFactory = FeedbackFactory;
        //
        //$scope.show.basic_totals = true;
        //$scope.show.budget_totals = true;
        ////$scope.tab = 'flex';
        //$scope.newBudget = {
        //    type: 'fixed'
        //};
        //
        ///**
        // * Watches
        // */
        //
        //$scope.$watch('feedbackFactory.data', function (newValue, oldValue, scope) {
        //    if (newValue && newValue.message) {
        //        scope.provideFeedback(newValue.message);
        //    }
        //});
        //
        //$scope.insertBudget = function ($keycode) {
        //    if ($keycode !== 13) {
        //        return;
        //    }
        //
        //    var $budget = $scope.newBudget;
        //
        //    $scope.clearTotalChanges();
        //    $scope.showLoading();
        //    $budget.sql_starting_date = $scope.formatDate($budget.starting_date);
        //    BudgetsFactory.insert($budget)
        //        .then(function (response) {
        //            $scope.jsInsertBudget(response);
        //            $scope.provideFeedback('Budget created');
        //
        //            $scope.hideLoading();
        //        })
        //        .catch(function (response) {
        //            $scope.responseError(response);
        //        });
        //};
        //
        ///**
        // * Add the budget to the JS array
        // */
        //$scope.jsInsertBudget = function (response) {
        //    var $budget = response.data;
        //    if ($budget.type === 'fixed') {
        //        $scope.fixedBudgets.push($budget);
        //    }
        //    else if ($budget.type === 'flex') {
        //        $scope.flexBudgets.push($budget);
        //    }
        //};
        //
        ///**
        // * For updating budget (name, type, amount, starting date) for an existing budget
        // */
        //$scope.updateBudget = function () {
        //    $scope.clearTotalChanges();
        //    $scope.showLoading();
        //    $scope.budget_popup.sqlStartingDate = $scope.formatDate($scope.budget_popup.formattedStartingDate);
        //    BudgetsFactory.update($scope.budget_popup)
        //        .then(function (response) {
        //            $scope.jsUpdateBudget(response);
        //            $scope.getTotals();
        //            //$scope.updateTotalsAfterResponse(response);
        //            $scope.show.popups.budget = false;
        //        })
        //        .catch(function (response) {
        //            $scope.responseError(response);
        //        });
        //};
        //
        //$scope.jsUpdateBudget = function (response) {
        //    //todo: allow for if budget type is changed. I will have to remove the budget from the table it was in
        //    var $budget = response.data;
        //    if ($budget.type === 'flex') {
        //        var $index = _.indexOf($scope.flexBudgets, _.findWhere($scope.flexBudgets, {id: response.data.id}));
        //        $scope.flexBudgets[$index] = response.data;
        //    }
        //    else if ($budget.type === 'fixed') {
        //        var $index = _.indexOf($scope.fixedBudgets, _.findWhere($scope.fixedBudgets, {id: response.data.id}));
        //        $scope.fixedBudgets[$index] = response.data;
        //    }
        //
        //};
        //
        ////$scope.handleUpdateResponse = function (response, $message) {
        ////    FilterFactory.updateDataForControllers(response.data);
        ////    $scope.updateTotalsAfterResponse(response);
        ////    $scope.hideLoading();
        ////    $scope.provideFeedback($message);
        ////};
        //
        //$scope.deleteBudget = function ($budget) {
        //    if (confirm("Are you sure you want to delete this budget?")) {
        //        $scope.showLoading();
        //        BudgetsFactory.destroy($budget)
        //            .then(function (response) {
        //                $scope.getTotals();
        //                $scope.jsDeleteBudget($budget);
        //                $scope.hideLoading();
        //            })
        //            .catch(function (response) {
        //                $scope.responseError(response);
        //            });
        //    }
        //};
        //
        //$scope.jsDeleteBudget = function ($budget) {
        //    if ($budget.type === 'fixed') {
        //        var $index = _.indexOf($scope.fixedBudgets, _.findWhere($scope.fixedBudgets, {id: $budget.id}));
        //        $scope.fixedBudgets = _.without($scope.fixedBudgets, $budget);
        //    }
        //    else if ($budget.type === 'flex') {
        //        var $index = _.indexOf($scope.flexBudgets, _.findWhere($scope.flexBudgets, {id: $budget.id}));
        //        $scope.flexBudgets = _.without($scope.flexBudgets, $budget);
        //    }
        //
        //};


        //$scope.updateTag = function ($tag, response) {
        //    var $index = _.indexOf($scope.tags, _.findWhere($scope.tags, {id: $tag.id}));
        //    $scope.tags[$index] = response.data.tag;
        //};

        /**
         * Return true if tag has a budget already
         * @returns {boolean}
         */
        //$scope.tagHasBudget = function ($new) {
        //    if ($new.flex_budget) {
        //        $scope.provideFeedback("You've got a flex budget for that tag.", 'error');
        //        return true;
        //    }
        //    else if ($new.fixed_budget) {
        //        $scope.provideFeedback("You've got a fixed budget for that tag.", 'error');
        //        return true;
        //    }
        //    return false;
        //};

        /**
         * Clear the tag inputs and focus the correct input
         * after entering a new budget
         * todo: clear the budget input
         * @param $type
         */
        //$scope.clearAndFocus = function ($type) {
        //    if ($type === 'fixed') {
        //        //I'm baffled as to why this works to clear the input when the ng-model is new_FB.
        //        //$scope.new_fixed_budget.tag.name = '';
        //
        //        $("#new-fixed-budget-name-input").val("").focus();
        //        $("#new-fixed-budget-SD").val("");
        //        $("#new-fixed-budget-amount").val("");
        //    }
        //    else {
        //        $("#new-flex-budget-name-input").val("").focus();
        //        $("#new-flex-budget-SD").val("");
        //        $("#new-flex-budget-amount").val("");
        //    }
        //};

        //$scope.removeBudget = function ($tag) {
        //    if (confirm("Remove " + $tag.budget_type + " budget for " + $tag.name + "?")) {
        //        $scope.showLoading();
        //        BudgetsFactory.removeBudget($tag)
        //            .then(function (response) {
        //                $scope.updateTotalsAfterResponse(response);
        //                $scope.updateTag($tag, response);
        //                $scope.provideFeedback('Budget deleted');
        //                $scope.hideLoading();
        //            })
        //            .catch(function (response) {
        //                $scope.responseError(response);
        //            });
        //    }
        //};

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