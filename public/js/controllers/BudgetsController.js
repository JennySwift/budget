(function () {

    angular
        .module('budgetApp')
        .controller('budgets', budgets);

    function budgets ($scope, $http, budgets, totals, autocomplete, settings, savings) {
        /**
         * scope properties
         */

        $scope.me = me;
        $scope.totals = {
            changes: {
                RB: [],
                RBWEFLB: []
            }
        };
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
            settings.getTags()
                .then(function (response) {
                    $scope.tags = response.data;
                    $scope.autocomplete.tags = response.data;
                })
                .catch(function (response) {
                    $scope.provideFeedback('There was an error');
                });
        };

        /**
         * filter
         */

        /**
         * Almost duplicate of filterTags in controller.js
         * @param $keycode
         * @param $typing
         * @param $location_for_tags
         * @param $scope_property
         */
        $scope.filterTags = function ($keycode, $typing, $location_for_tags, $scope_property) {
            if ($keycode !== 38 && $keycode !== 40 && $keycode !== 13) {
                //not up arrow, down arrow or enter, so filter tags
                autocomplete.removeSelected($scope.tags);
                $scope[$scope_property]['dropdown'] = true;
                $scope.autocomplete.tags = autocomplete.filterTags($scope.tags, $typing);
                if ($typing !== "" && $scope.autocomplete.tags.length > 0) {
                    $scope.selected = autocomplete.selectFirstItem($scope.autocomplete.tags);
                }
            }
            else if ($keycode === 38) {
                //up arrow
                $scope.selected = autocomplete.upArrow($scope.autocomplete.tags);
            }
            else if ($keycode === 40) {
                //down arrow
                $scope.selected = autocomplete.downArrow($scope.autocomplete.tags);
            }
            else if ($keycode === 13) {
                if ($location_for_tags === $scope.new_fixed_budget.tag) {
                    //We are just autocompleting the budget tag input, not adding a tag anywhere
                    if (!$typing) {
                        return;
                    }
                    $scope.autocompleteFixedBudget();
                    return;
                }
                else if ($location_for_tags === $scope.new_flex_budget.tag) {
                    //We are just autocompleting the budget tag input, not adding a tag anywhere
                    if (!$typing) {
                        return;
                    }
                    $scope.autocompleteFlexBudget();
                    return;
                }

                //resetting the dropdown to show all the tags again after a tag has been added
                $scope.autocomplete.tags = $scope.tags;
            }
        };

        $scope.clearChanges = function () {
            $scope.totals.changes = {
                RB: [],
                RBWEFLB: []
            };
        };

        /**
         * insert
         */

        /**
         * update
         */

        $scope.updateFixedBudget = function ($keycode) {
            if ($keycode !== 13) {
                return;
            }
            budgets.updateBudget($scope.new_fixed_budget.tag.id, 'fixed_budget', $scope.new_fixed_budget.budget)
                .then(function (response) {
                    $scope.getTotals();
                    //unselect the tag in the dropdown
                    _.findWhere($scope.tags, {selected: true}).selected = false;
                    //clear the tag inputs and focus the correct input
                    $scope.new_fixed_budget.tag.name = "";
                    $scope.new_fixed_budget.budget = "";
                    $("#budget-fixed-tag-input").focus();
                })
                .catch(function (response) {
                    $scope.provideFeedback('There was an error');
                });
        };

        $scope.updateFlexBudget = function ($keycode) {
            if ($keycode !== 13) {
                return;
            }
            budgets.updateBudget($scope.new_flex_budget.tag.id, 'flex_budget', $scope.new_flex_budget.budget)
                .then(function (response) {
                    $scope.getTotals();
                    //unselect the tag in the dropdown
                    _.findWhere($scope.tags, {selected: true}).selected = false;
                    //clear the tag inputs and focus the correct input
                    $scope.new_flex_budget.tag.name = "";
                    $scope.new_flex_budget.budget = "";
                    $("#budget-flex-tag-input").focus();
                })
                .catch(function (response) {
                    $scope.provideFeedback('There was an error');
                });
        };

        $scope.removeFixedBudget = function ($tag_id, $tag_name) {
            if (confirm("remove fixed budget for " + $tag_name + "?")) {
                budgets.updateBudget($tag_id, 'fixed_budget', 'NULL')
                    .then(function (response) {
                        $scope.getTotals();
                    })
                    .catch(function (response) {
                        $scope.provideFeedback('There was an error');
                    });
            }
        };

        $scope.removeFlexBudget = function ($tag_id, $tag_name) {
            if (confirm("remove flex budget for " + $tag_name + "?")) {
                budgets.updateBudget($tag_id, 'flex_budget', 'NULL')
                    .then(function (response) {
                        $scope.getTotals();
                    })
                    .catch(function (response) {
                        $scope.provideFeedback('There was an error');
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
                    $scope.getTotals();
                    $scope.show.edit_CSD = false;
                })
                .catch(function (response) {
                    $scope.provideFeedback('There was an error');
                });
        };

        /**
         * delete
         */

        /**
         * autocomplete
         */

        $scope.autocompleteFixedBudget = function () {
            $scope.autocomplete.tags = $scope.tags;
            $scope.new_fixed_budget.tag.id = $scope.selected.id;
            $scope.new_fixed_budget.tag.name = $scope.selected.name;
            $scope.new_fixed_budget.tag.fixed_budget = $scope.selected.fixed_budget;
            $scope.new_fixed_budget.tag.flex_budget = $scope.selected.flex_budget;
            $scope.new_fixed_budget.dropdown = false;

            if ($scope.new_fixed_budget.tag.flex_budget) {
                $scope.new_fixed_budget.tag = {};
                $scope.selected = {};
                alert("You've got a flex budget for that tag.");
                return;
            }

            $("#budget-fixed-tag-input").val($scope.selected.name);
            $("#budget-fixed-budget-input").focus();
        };

        $scope.autocompleteFlexBudget = function () {
            $scope.autocomplete.tags = $scope.tags;
            $scope.new_flex_budget.tag.id = $scope.selected.id;
            $scope.new_flex_budget.tag.name = $scope.selected.name;
            $scope.new_flex_budget.tag.fixed_budget = $scope.selected.fixed_budget;
            $scope.new_flex_budget.tag.flex_budget = $scope.selected.flex_budget;
            $scope.new_flex_budget.dropdown = false;

            if ($scope.new_flex_budget.tag.fixed_budget) {
                $scope.new_flex_budget.tag = {};
                $scope.selected = {};
                alert("You've got a fixed budget for that tag.");
                return;
            }

            $("#budget-flex-tag-input").val($scope.selected.name);
            $("#budget-flex-budget-input").focus();
        };

        /**
         * delete
         */

        /**
         * totals-duplicates from main controller
         */

        $scope.getTotals = function () {
            totals.basicTotals()
                .then(function (response) {
                    $scope.totals.basic = response.data;
                })
                .catch(function (response) {
                    $scope.provideFeedback('There was an error');
                });
            totals.budget()
                .then(function (response) {
                    $scope.totals.budget = response.data;
                })
                .catch(function (response) {
                    $scope.provideFeedback('There was an error');
                });
        };

        $scope.updateSavingsTotal = function ($keycode) {
            if ($keycode !== 13) {
                return;
            }
            savings.updatesavingsTotal()
                .then(function (response) {
                    $scope.totals.basic.savings_total = response.data;
                    $scope.show.savings_total.input = false;
                    $scope.show.savings_total.edit_btn = true;
                    $scope.getTotals();
                })
                .catch(function (response) {
                    $scope.provideFeedback('There was an error');
                });
        };

        $scope.addFixedToSavings = function ($keycode) {
            if ($keycode !== 13) {
                return;
            }
            savings.addFixedToSavings()
                .then(function (response) {
                    $scope.totals.basic.savings_total = response.data;
                    $scope.getTotals();
                })
                .catch(function (response) {
                    $scope.provideFeedback('There was an error');
                });
        };

        $scope.addPercentageToSavingsAutomatically = function ($amount_to_add) {
            savings.addPercentageToSavingsAutomatically($amount_to_add)
                .then(function (response) {
                    $scope.totals.basic.savings_total = response.data;
                    $scope.getTotals();
                })
                .catch(function (response) {
                    $scope.provideFeedback('There was an error');
                });
        };

        $scope.reverseAutomaticInsertIntoSavings = function ($amount_to_subtract) {
            savings.reverseAutomaticInsertIntoSavings($amount_to_subtract)
                .then(function (response) {
                    $scope.totals.basic.savings_total = response.data;
                    $scope.getTotals();
                })
                .catch(function (response) {
                    $scope.provideFeedback('There was an error');
                });
        };

        $scope.addPercentageToSavings = function ($keycode) {
            if ($keycode !== 13) {
                return;
            }
            savings.addPercentageToSavings()
                .then(function (response) {
                    $scope.totals.basic.savings_total = response.data;
                    $scope.getTotals();
                })
                .catch(function (response) {
                    $scope.provideFeedback('There was an error');
                });
        };

        $scope.$watch('totals.budget.RB', function (newValue, oldValue) {
            //Before the refactor I didn't need this if check. Not sure why I need it now or it errors on page load.
            if (!newValue || !oldValue) {
                return;
            }
            //get rid of the commas and convert to integers
            var $new_RB = parseInt(newValue.replace(',', ''), 10);
            var $old_RB = parseInt(oldValue.replace(',', ''), 10);
            if ($new_RB > $old_RB) {
                //$RB has increased due to a user action
                //Figure out how much it has increased by.
                var $diff = $new_RB - $old_RB;
                //This value will change. Just for developing purposes.
                var $percent = 10;
                var $amount_to_add = $diff / 100 * $percent;
                $scope.addPercentageToSavingsAutomatically($amount_to_add);
            }
        });

        /**
         * Notify user when totals change.
         * Duplicate code from main controller
         */

            //Credit
        $scope.$watch('totals.basic.credit', function (newValue, oldValue) {
            if (!oldValue || newValue === oldValue) {
                return;
            }
            $scope.totals.changes.credit = newValue - oldValue;
        });

        //RFB
        $scope.$watch('totals.budget.FB.totals.remaining', function (newValue, oldValue) {
            if (!oldValue || newValue === oldValue) {
                return;
            }
            $scope.totals.changes.RFB = newValue - oldValue;
        });

        //RBWEFLB
        $scope.$watch('totals.budget.RBWEFLB', function (newValue, oldValue) {
            if (!oldValue || newValue === oldValue) {
                return;
            }
            $scope.totals.changes.RBWEFLB.push(newValue - oldValue);
        });

        //CFB
        $scope.$watch('totals.budget.FB.totals.cumulative_budget', function (newValue, oldValue) {
            if (!oldValue || newValue === oldValue) {
                return;
            }
            $scope.totals.changes.CFB = newValue - oldValue;
        });

        //EWB
        $scope.$watch('totals.basic.expense_without_budget_total', function (newValue, oldValue) {
            if (!oldValue || newValue === oldValue) {
                return;
            }
            $scope.totals.changes.EWB = newValue - oldValue;
        });

        //EFLB
        $scope.$watch('totals.basic.EFLB', function (newValue, oldValue) {
            if (!oldValue || newValue === oldValue) {
                return;
            }
            $scope.totals.changes.EFLB = newValue - oldValue;
        });

        //EFBBCSD
        $scope.$watch('totals.budget.FB.totals.spent_before_CSD', function (newValue, oldValue) {
            if (!oldValue || newValue === oldValue) {
                return;
            }
            $scope.totals.changes.EFBBCSD = newValue - oldValue;
        });

        //EFBACSD
        $scope.$watch('totals.budget.FB.totals.spent', function (newValue, oldValue) {
            if (!oldValue || newValue === oldValue) {
                return;
            }
            $scope.totals.changes.EFBACSD = newValue - oldValue;
        });

        //Savings
        $scope.$watch(' totals.basic.savings_total', function (newValue, oldValue) {
            if (!oldValue || newValue === oldValue) {
                return;
            }
            $scope.totals.changes.savings = newValue - oldValue;
        });

        //RB
        $scope.$watch('totals.budget.RB', function (newValue, oldValue) {
            if (!oldValue || newValue === oldValue) {
                return;
            }
            $scope.totals.changes.RB.push(newValue - oldValue);
        });

        //Debit
        $scope.$watch('totals.basic.debit', function (newValue, oldValue) {
            if (!oldValue || newValue === oldValue) {
                return;
            }
            $scope.totals.changes.debit = newValue - oldValue;
        });

        //Balance
        $scope.$watch('totals.basic.balance', function (newValue, oldValue) {
            if (!oldValue || newValue === oldValue) {
                return;
            }
            $scope.totals.changes.balance = newValue - oldValue;
        });

        //Reconciled
        $scope.$watch('totals.basic.reconciled_sum', function (newValue, oldValue) {
            if (!oldValue || newValue === oldValue) {
                return;
            }
            $scope.totals.changes.reconciled = newValue - oldValue;
        });

        /**
         * page load
         */

        $scope.getTotals();
        $scope.getTags();
    }

})();