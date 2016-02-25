var BudgetsPage = Vue.component('budgets-page', {
    template: '#budgets-page-template',
    data: function () {
        return {
            showBasicTotals: true,
            showBudgetTotals: true,
            newBudget:  {
                type: 'fixed'
            }
        };
    },
    components: {},
    methods: {
        toggleNewBudget: function () {
            $scope.show.newBudget = true;
        },

        listen: function () {
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
        },

        insertBudget: function ($keycode) {
            if ($keycode !== 13) {
                return;
            }

            var $budget = $scope.newBudget;

            $scope.clearTotalChanges();
            $scope.showLoading();
            $budget.sql_starting_date = $filter('formatDate')($budget.starting_date);
            BudgetsFactory.insert($budget)
                .then(function (response) {
                    jsInsertBudget(response);
                    $scope.$emit('getSideBarTotals');
                    $rootScope.$broadcast('provideFeedback', 'Budget created');
                    updateBudgetTableTotals($budget);
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        },
        deleteBudget: function ($budget) {
            $scope.showLoading();
            if (confirm('You have ' + $budget.transactionsCount + ' transactions with this budget. Are you sure you want to delete it?')) {
                $scope.showLoading();
                BudgetsFactory.destroy($budget)
                    .then(function (response) {
                        $scope.$emit('getSideBarTotals');
                        updateBudgetTableTotals($budget);
                        jsDeleteBudget($budget);
                        $scope.hideLoading();
                        $rootScope.$broadcast('provideFeedback', 'Budget deleted');
                    })
                    .catch(function (response) {
                        $scope.responseError(response);
                    });
            }
            else {
                $scope.hideLoading();
            }
        },

        jsDeleteBudget: function ($budget) {
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
        },

        showBudgetPopup: function ($tag, $type) {
            $scope.budget_popup = $tag;
            $scope.budget_popup.type = $type;
            $scope.show.popups.budget = true;
        },

        /**
         * For updating budget (name, type, amount, starting date) for an existing budget
         */
        updateBudget: function () {
            $scope.clearTotalChanges();
            $scope.showLoading();
            $scope.budget_popup.sqlStartingDate = $filter('formatDate')($scope.budget_popup.formattedStartingDate);
            BudgetsFactory.update($scope.budget_popup)
                .then(function (response) {
                    var $budget = response.data.data;
                    jsUpdateBudget($budget);
                    updateBudgetTableTotals($budget);
                    $scope.hideLoading();
                    $rootScope.$broadcast('provideFeedback', 'Budget updated');
                    $scope.$emit('getSideBarTotals');
                    $scope.show.popups.budget = false;
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        },

        jsUpdateBudget: function ($budget) {
            //todo: allow for if budget type is changed. I will have to remove the budget from the table it was in
            if ($budget.type === 'flex') {
                var $index = _.indexOf($scope.flexBudgets, _.findWhere($scope.flexBudgets, {id: $budget.id}));
                $scope.flexBudgets[$index] = $budget;
            }
            else if ($budget.type === 'fixed') {
                var $index = _.indexOf($scope.fixedBudgets, _.findWhere($scope.fixedBudgets, {id: $budget.id}));
                $scope.fixedBudgets[$index] = $budget;
            }
        },

        updateBudgetTableTotal: function ($budget) {
            if ($budget.type === 'fixed' && page === 'fixedBudgets') {
                $scope.getFixedBudgetTotals();
            }
            else if ($budget.type === 'flex' && page === 'flexBudgets') {
                $scope.getFlexBudgetTotals();
            }
        },

        /**
         * Add the budget to the JS array
         */
        jsInsertBudget: function (response) {
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
        },


    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        this.listen();
    }
});

//insert: function ($budget) {
//    var $url = '/api/budgets';
//
//    var $data = {
//        type: $budget.type,
//        name: $budget.name,
//        amount: $budget.amount,
//        starting_date: $budget.sql_starting_date
//    };
//
//    return $http.post($url, $data);
//},
//
//update: function ($budget) {
//    var $url = $budget.path;
//
//    var $data = {
//        id: $budget.id,
//        name: $budget.name,
//        type: $budget.type,
//        amount: $budget.amount,
//        starting_date: $budget.sqlStartingDate
//    };
//
//    return $http.put($url, $data);
//},
//
//destroy: function ($budget) {
//    var $url = $budget.path;
//
//    return $http.delete($url);
//}