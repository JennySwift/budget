;(function(){
    'use strict';
    angular
        .module('budgetApp')
        .directive('totalsDirective', totals);

    /* @inject */
    function totals(SavingsFactory, FilterFactory) {
        return {
            restrict: 'EA',
            scope: {
                //"totals": "=totals",
                "basicTotals": "=basictotals",
                "fixedBudgetTotals": "=fixedbudgettotals",
                "flexBudgetTotals": "=flexbudgettotals",
                "remainingBalance": "=remainingbalance",
                "provideFeedback" : "&providefeedback",
                "show": "=show"
            },
            templateUrl: 'totals-directive',
            //scope: true,
            link: function($scope, elem, attrs) {
                $scope.filterFactory = FilterFactory;
                //$scope.show = {
                //    basic_totals: true,
                //    budget_totals: true
                //};

                $scope.totalChanges = {
                    //RB: [],
                    //RBWEFLB: []
                };

                $scope.clearChanges = function () {
                    $scope.totalChanges = {
                        //RB: [],
                        //RBWEFLB: []
                    };
                };

                //$scope.$watch('filterFactory.totals', function (newValue, oldValue, scope) {
                //    if (newValue) {
                //        scope.totals.basic = newValue.basic;
                //        scope.totals.budget = newValue.budget;
                //    }
                //});

                $scope.$watch('filterFactory.basicTotals', function (newValue, oldValue, scope) {

                    if (newValue) {

                        if (newValue.credit !== $scope.basicTotals.credit) {
                            $scope.totalChanges.credit = $scope.format(newValue.credit, $scope.basicTotals.credit);
                        }

                        if (newValue.debit !== $scope.basicTotals.debit) {
                            $scope.totalChanges.debit = $scope.format(newValue.debit, $scope.basicTotals.debit);
                        }

                        if (newValue.balance !== $scope.basicTotals.balance) {
                            $scope.totalChanges.balance = $scope.format(newValue.balance, $scope.basicTotals.balance);
                        }

                        if (newValue.reconciledSum !== $scope.basicTotals.reconciledSum) {
                            $scope.totalChanges.reconciled = $scope.format(newValue.reconciledSum, $scope.basicTotals.reconciledSum);
                        }

                        if (newValue.savings !== $scope.basicTotals.savings) {
                            $scope.totalChanges.savings = $scope.format(newValue.savings, $scope.basicTotals.savings);
                        }

                        if (newValue.EWB !== $scope.basicTotals.EWB) {
                            $scope.totalChanges.EWB = $scope.format(newValue.EWB, $scope.basicTotals.EWB);
                        }

                        scope.basicTotals = newValue;
                    }
                });

                $scope.$watch('filterFactory.fixedBudgetTotals', function (newValue, oldValue, scope) {
                    if (newValue) {

                        if (newValue.remaining !== $scope.fixedBudgetTotals.remaining) {
                            $scope.totalChanges.remainingFixedBudget = $scope.format(newValue.remaining, $scope.fixedBudgetTotals.remaining);
                        }

                        if (newValue.cumulative !== $scope.fixedBudgetTotals.cumulative) {
                            $scope.totalChanges.cumulativeFixedBudget = $scope.format(newValue.cumulative, $scope.fixedBudgetTotals.cumulative);
                        }

                        if (newValue.spentBeforeStartingDate !== $scope.fixedBudgetTotals.spentBeforeStartingDate) {
                            $scope.totalChanges.fixedBudgetExpensesBeforeStartingDate = $scope.format(newValue.spentBeforeStartingDate, $scope.fixedBudgetTotals.spentBeforeStartingDate);
                        }

                        if (newValue.spentAfterStartingDate !== $scope.fixedBudgetTotals.spentAfterStartingDate) {
                            $scope.totalChanges.fixedBudgetExpensesAfterStartingDate = $scope.format(newValue.spentAfterStartingDate, $scope.fixedBudgetTotals.spentAfterStartingDate);
                        }

                        scope.fixedBudgetTotals = newValue;
                    }
                });

                $scope.$watch('filterFactory.flexBudgetTotals', function (newValue, oldValue, scope) {
                    if (newValue) {

                        if (newValue.spentBeforeStartingDate !== $scope.flexBudgetTotals.spentBeforeStartingDate) {
                            $scope.totalChanges.flexBudgetExpensesBeforeStartingDate = $scope.format(newValue.spentBeforeStartingDate, $scope.flexBudgetTotals.spentBeforeStartingDate);
                        }

                        if (newValue.spentAfterStartingDate !== $scope.flexBudgetTotals.spentAfterStartingDate) {
                            $scope.totalChanges.flexBudgetExpensesAfterStartingDate = $scope.format(newValue.spentAfterStartingDate, $scope.flexBudgetTotals.spentAfterStartingDate);
                        }

                        scope.flexBudgetTotals = newValue;
                    }
                });

                $scope.$watch('filterFactory.remainingBalance', function (newValue, oldValue, scope) {
                    if (newValue) {

                        if (newValue !== $scope.remainingBalance) {
                            $scope.totalChanges.remainingBalance = $scope.format(newValue, $scope.remainingBalance);
                        }

                        scope.remainingBalance = newValue;
                    }
                });

                /**
                 * End watches
                 */

                /**
                 * For formatting the numbers in the total changes to two decimal places
                 * @param newValue
                 * @param oldValue
                 * @returns {string}
                 */
                $scope.format = function (newValue, oldValue) {
                    //var $diff = newValue.replace(',', '') - oldValue.replace(',', '');
                    var $diff = newValue - oldValue;
                    return $diff.toFixed(2);
                };

                $scope.showSavingsTotalInput = function () {
                    $scope.show.savings_total.input = true;
                    $scope.show.savings_total.edit_btn = false;
                };
            }
        };
    }
}).call(this);

