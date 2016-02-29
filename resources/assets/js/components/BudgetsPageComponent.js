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

        initialize: function () {
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
        this.initialize();
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