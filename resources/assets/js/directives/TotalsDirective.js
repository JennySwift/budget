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
                "totalChanges": "=totalchanges",
                "provideFeedback" : "&providefeedback",
                "show": "=show"
            },
            templateUrl: '/totals-directive',
            //scope: true,
            link: function($scope, elem, attrs) {
                //$scope.filterFactory = FilterFactory;
                //$scope.show = {
                //    basic_totals: true,
                //    budget_totals: true
                //};

                //$scope.$watch('filterFactory.totals', function (newValue, oldValue, scope) {
                //    if (newValue) {
                //        scope.totals.basic = newValue.basic;
                //        scope.totals.budget = newValue.budget;
                //    }
                //});

                $scope.$watch('basicTotals', function (newValue, oldValue, scope) {

                    if (newValue) {

                        if (newValue.credit !== oldValue.credit) {
                            $scope.totalChanges.credit = $scope.calculateDifference(newValue.credit, oldValue.credit);
                        }

                        if (newValue.debit !== oldValue.debit) {
                            $scope.totalChanges.debit = $scope.calculateDifference(newValue.debit, oldValue.debit);
                        }

                        if (newValue.balance !== oldValue.balance) {
                            $scope.totalChanges.balance = $scope.calculateDifference(newValue.balance, oldValue.balance);
                        }

                        if (newValue.reconciledSum !== oldValue.reconciledSum) {
                            $scope.totalChanges.reconciledSum = $scope.calculateDifference(newValue.reconciledSum, oldValue.reconciledSum);
                        }

                        if (newValue.savings !== oldValue.savings) {
                            $scope.totalChanges.savings = $scope.calculateDifference(newValue.savings, oldValue.savings);
                        }

                        if (newValue.EWB !== oldValue.EWB) {
                            $scope.totalChanges.EWB = $scope.calculateDifference(newValue.EWB, oldValue.EWB);
                        }

                        scope.basicTotals = newValue;
                    }
                });

                $scope.$watch('fixedBudgetTotals', function (newValue, oldValue, scope) {
                    if (newValue) {

                        if (newValue.remaining !== oldValue.remaining) {
                            $scope.totalChanges.remainingFixedBudget = $scope.calculateDifference(newValue.remaining, oldValue.remaining);
                        }

                        if (newValue.cumulative !== oldValue.cumulative) {
                            $scope.totalChanges.cumulativeFixedBudget = $scope.calculateDifference(newValue.cumulative, oldValue.cumulative);
                        }

                        if (newValue.spentBeforeStartingDate !== oldValue.spentBeforeStartingDate) {
                            $scope.totalChanges.fixedBudgetExpensesBeforeStartingDate = $scope.calculateDifference(newValue.spentBeforeStartingDate, oldValue.spentBeforeStartingDate);
                        }

                        if (newValue.spentAfterStartingDate !== oldValue.spentAfterStartingDate) {
                            $scope.totalChanges.fixedBudgetExpensesAfterStartingDate = $scope.calculateDifference(newValue.spentAfterStartingDate, oldValue.spentAfterStartingDate);
                        }

                        scope.fixedBudgetTotals = newValue;
                    }
                });

                $scope.$watch('flexBudgetTotals', function (newValue, oldValue, scope) {
                    if (newValue) {

                        if (newValue.spentBeforeStartingDate !== oldValue.spentBeforeStartingDate) {
                            $scope.totalChanges.flexBudgetExpensesBeforeStartingDate = $scope.calculateDifference(newValue.spentBeforeStartingDate, oldValue.spentBeforeStartingDate);
                        }

                        if (newValue.spentAfterStartingDate !== oldValue.spentAfterStartingDate) {
                            $scope.totalChanges.flexBudgetExpensesAfterStartingDate = $scope.calculateDifference(newValue.spentAfterStartingDate, oldValue.spentAfterStartingDate);
                        }

                        scope.flexBudgetTotals = newValue;
                    }
                });

                $scope.$watch('remainingBalance', function (newValue, oldValue, scope) {
                    if (newValue) {

                        if (newValue !== oldValue) {
                            $scope.totalChanges.remainingBalance = $scope.calculateDifference(newValue, oldValue);
                        }

                        scope.remainingBalance = newValue;
                    }
                });

                /**
                 * End watches
                 */

                /**
                 * @param newValue
                 * @param oldValue
                 * @returns {string}
                 */
                $scope.calculateDifference = function (newValue, oldValue) {
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

