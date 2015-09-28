(function () {

    angular
        .module('budgetApp')
        .controller('BudgetsController', budgets);

    function budgets ($scope, BudgetsFactory, TotalsFactory) {

        $scope.toggleNewBudget = function () {
            $scope.show.newBudget = true;
        };

        if (typeof fixedBudgets !== 'undefined') {
            $scope.fixedBudgets = fixedBudgets;
        }

        if (typeof flexBudgets !== 'undefined') {
            $scope.flexBudgets = flexBudgets;
        }

        if (typeof unassignedBudgets !== 'undefined') {
            $scope.unassignedBudgets = unassignedBudgets;
        }

        if (page === 'fixedBudgets') {
            $scope.fixedBudgetTotals = fixedBudgetTotals;

            $scope.getFixedBudgetTotals = function () {
                $scope.showLoading();
                TotalsFactory.getFixedBudgetTotals()
                    .then(function (response) {
                        $scope.fixedBudgetTotals = response.data;
                        $scope.hideLoading();
                    })
                    .catch(function (response) {
                        $scope.responseError(response);
                    });
            };
        }

        else if (page === 'flexBudgets') {
            $scope.flexBudgetTotals = flexBudgetTotals;

            $scope.getFlexBudgetTotals = function () {
                $scope.showLoading();
                TotalsFactory.getFlexBudgetTotals()
                    .then(function (response) {
                        $scope.flexBudgetTotals = response.data;
                        $scope.hideLoading();
                    })
                    .catch(function (response) {
                        $scope.responseError(response);
                    });
            };
        }

        $scope.show.basic_totals = true;
        $scope.show.budget_totals = true;
        $scope.newBudget = {
            type: 'fixed'
        };

        $scope.insertBudget = function ($keycode) {
            if ($keycode !== 13) {
                return;
            }

            var $budget = $scope.newBudget;

            $scope.clearTotalChanges();
            $scope.showLoading();
            $budget.sql_starting_date = $scope.formatDate($budget.starting_date);
            BudgetsFactory.insert($budget)
                .then(function (response) {
                    jsInsertBudget(response);
                    $scope.getSideBarTotals();
                    $scope.provideFeedback('Budget created');

                    if ($budget.type === 'fixed' && page === 'fixedBudgets') {
                        $scope.getFixedBudgetTotals();
                    }
                    else if ($budget.type === 'flex' && page === 'flexBudgets') {
                        $scope.getFlexBudgetTotals();
                    }

                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };

        /**
        * Add the budget to the JS array
        */
        function jsInsertBudget (response) {
            var $budget = response.data.data;
            if ($budget.type === 'fixed' && page === 'fixedBudgets') {
                $scope.fixedBudgets.push($budget);
            }
            else if ($budget.type === 'flex' && page === 'flexBudgets') {
                $scope.flexBudgets.push($budget);
            }
            else if ($budget.type === 'unassigned' && page === 'unassignedBudgets') {
                $scope.unassignedBudgets.push($budget);
            }
        }

        /**
        * For updating budget (name, type, amount, starting date) for an existing budget
        */
        $scope.updateBudget = function () {
            $scope.clearTotalChanges();
            $scope.showLoading();
            $scope.budget_popup.sqlStartingDate = $scope.formatDate($scope.budget_popup.formattedStartingDate);
            BudgetsFactory.update($scope.budget_popup)
                .then(function (response) {
                    jsUpdateBudget(response);
                    $scope.getSideBarTotals();
                    $scope.show.popups.budget = false;
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };

        function jsUpdateBudget (response) {
            //todo: allow for if budget type is changed. I will have to remove the budget from the table it was in
            var $budget = response.data;
            if ($budget.type === 'flex') {
                var $index = _.indexOf($scope.flexBudgets, _.findWhere($scope.flexBudgets, {id: response.data.id}));
                $scope.flexBudgets[$index] = response.data;
            }
            else if ($budget.type === 'fixed') {
                var $index = _.indexOf($scope.fixedBudgets, _.findWhere($scope.fixedBudgets, {id: response.data.id}));
                $scope.fixedBudgets[$index] = response.data;
            }
        }

        $scope.deleteBudget = function ($budget) {
            $scope.showLoading();
            if (confirm('You have ' + $budget.transactionsCount + ' transactions with this budget. Are you sure you want to delete it?')) {
                $scope.showLoading();
                BudgetsFactory.destroy($budget)
                    .then(function (response) {
                        $scope.getSideBarTotals();
                        jsDeleteBudget($budget);
                        $scope.hideLoading();
                        $scope.provideFeedback('Budget deleted');
                    })
                    .catch(function (response) {
                        $scope.responseError(response);
                    });
            }
            else {
                $scope.hideLoading();
            }
        };

        function jsDeleteBudget ($budget) {
            var $index;

            if ($budget.type === 'fixed') {
                $index = _.indexOf($scope.fixedBudgets, _.findWhere($scope.fixedBudgets, {id: $budget.id}));
                $scope.fixedBudgets = _.without($scope.fixedBudgets, $budget);
            }
            else if ($budget.type === 'flex') {
                $index = _.indexOf($scope.flexBudgets, _.findWhere($scope.flexBudgets, {id: $budget.id}));
                $scope.flexBudgets = _.without($scope.flexBudgets, $budget);
            }
            else if ($budget.type === 'unassigned') {
                $index = _.indexOf($scope.unassignedBudgets, _.findWhere($scope.unassignedBudgets, {id: $budget.id}));
                $scope.unassignedBudgets = _.without($scope.unassignedBudgets, $budget);
            }
        }

        $scope.showBudgetPopup = function ($tag, $type) {
            $scope.budget_popup = $tag;
            $scope.budget_popup.type = $type;
            $scope.show.popups.budget = true;
        };

    }

})();