(function () {

    angular
        .module('budgetApp')
        .controller('budgets', budgets);

    function budgets ($scope, $http, budgets, totals, autocomplete, savings, TagsFactory, FilterFactory, FeedbackFactory) {
        /**
         * scope properties
         */

        $scope.me = me;

        $scope.totals = totals_response;

        $scope.tags = tags_response;

        $scope.show = {
            basic_totals: true,
            budget_totals: true
        };
        $scope.autocomplete = {};

        $scope.new_fixed_budget = {
            tag: {
                name: "",
                id: ""
            },
            budget: "",
            dropdown: false
        };

        $scope.new_flex_budget = {
            tag: {
                name: "",
                id: ""
            },
            budget: "",
            dropdown: false
        };

        /**
         * select
         */

        $scope.getTags = function () {
            TagsFactory.getTags()
                .then(function (response) {
                    $scope.tags = response.data;
                    $scope.autocomplete.tags = response.data;
                })
                .catch(function (response) {
                    FeedbackFactory.provideFeedback('There was an error');
                });
        };

        /**
         * update
         */

        $scope.updateFixedBudget = function ($keycode) {
            if ($keycode !== 13) {
                return;
            }
            budgets.updateBudget($scope.new_fixed_budget.tag.id, 'fixed_budget', $scope.new_fixed_budget.budget)
                .then(function (response) {
                    FilterFactory.updateDataForControllers(response.data);
                    $scope.totals.budget = response.data.budget;

                    //clear the tag inputs and focus the correct input
                    $scope.new_fixed_budget.tag.name = "";
                    $scope.new_fixed_budget.budget = "";
                    $("#budget-fixed-tag-input").focus();
                })
                .catch(function (response) {
                    FeedbackFactory.provideFeedback('There was an error');
                });
        };

        $scope.updateFlexBudget = function ($keycode) {
            if ($keycode !== 13) {
                return;
            }
            budgets.updateBudget($scope.new_flex_budget.tag.id, 'flex_budget', $scope.new_flex_budget.budget)
                .then(function (response) {
                    FilterFactory.updateDataForControllers(response.data);
                    $scope.totals.budget = response.data.budget;

                    //clear the tag inputs and focus the correct input
                    $scope.new_flex_budget.tag.name = "";
                    $scope.new_flex_budget.budget = "";
                    $("#budget-flex-tag-input").focus();
                })
                .catch(function (response) {
                    FeedbackFactory.provideFeedback('There was an error');
                });
        };

        $scope.removeFixedBudget = function ($tag_id, $tag_name) {
            if (confirm("remove fixed budget for " + $tag_name + "?")) {
                budgets.updateBudget($tag_id, 'fixed_budget', 'NULL')
                    .then(function (response) {
                        FilterFactory.updateDataForControllers(response.data);
                        $scope.totals.budget = response.data.budget;
                    })
                    .catch(function (response) {
                        FeedbackFactory.provideFeedback('There was an error');
                    });
            }
        };

        $scope.removeFlexBudget = function ($tag_id, $tag_name) {
            if (confirm("remove flex budget for " + $tag_name + "?")) {
                budgets.updateBudget($tag_id, 'flex_budget', 'NULL')
                    .then(function (response) {
                        FilterFactory.updateDataForControllers(response.data);
                        $scope.totals.budget = response.data.budget;
                    })
                    .catch(function (response) {
                        FeedbackFactory.provideFeedback('There was an error');
                    });
            }
        };

        $scope.updateCSDSetup = function ($tag) {
            $scope.edit_CSD = $tag;
            $scope.show.edit_CSD = true;
        };

        $scope.updateCSD = function () {
            budgets.updateCSD($scope.edit_CSD)
                .then(function (response) {
                    FilterFactory.updateDataForControllers(response.data);
                    $scope.totals.budget = response.data.budget;
                    $scope.show.edit_CSD = false;
                })
                .catch(function (response) {
                    FeedbackFactory.provideFeedback('There was an error');
                });
        };

        //Todo: add this code back in somewhere
        //if ($scope.new_fixed_budget.tag.flex_budget) {
        //    $scope.new_fixed_budget.tag = {};
        //    $scope.selected = {};
        //    alert("You've got a flex budget for that tag.");
        //    return;
        //}
        //
        //if ($scope.new_flex_budget.tag.fixed_budget) {
        //    $scope.new_flex_budget.tag = {};
        //    $scope.selected = {};
        //    alert("You've got a fixed budget for that tag.");
        //    return;
        //}

        /**
         * delete
         */

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