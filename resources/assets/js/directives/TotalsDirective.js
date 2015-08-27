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
                "totals": "=totals",
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

                $scope.totals.changes = {
                    //RB: [],
                    //RBWEFLB: []
                };

                $scope.clearChanges = function () {
                    $scope.totals.changes = {
                        //RB: [],
                        //RBWEFLB: []
                    };
                };

                $scope.$watch('filterFactory.totals', function (newValue, oldValue, scope) {
                    if (newValue) {
                        scope.totals.basic = newValue.basic;
                        scope.totals.budget = newValue.budget;
                    }
                });

                /**
                 * Notify user when totals change
                 */

                //Credit
                $scope.$watch('totals.basic.credit', function (newValue, oldValue) {
                    if (!oldValue || newValue === oldValue) {
                        return;
                    }
                    $scope.totals.changes.credit = $scope.format(newValue, oldValue);
                });

                //RFB
                $scope.$watch('totals.budget.FB.totals.remaining', function (newValue, oldValue) {
                    if (!oldValue || newValue === oldValue) {
                        return;
                    }
                    $scope.totals.changes.RFB = $scope.format(newValue, oldValue);
                });

                //CFB
                $scope.$watch('totals.budget.FB.totals.cumulative_budget', function (newValue, oldValue) {
                    if (!oldValue || newValue === oldValue) {
                        return;
                    }
                    $scope.totals.changes.CFB = $scope.format(newValue, oldValue);
                });

                //EWB
                $scope.$watch('totals.basic.EWB', function (newValue, oldValue) {
                    if (!oldValue || newValue === oldValue) {
                        return;
                    }
                    $scope.totals.changes.EWB = $scope.format(newValue, oldValue);
                });

                //EFBBSD
                $scope.$watch('totals.budget.FB.totals.spentBeforeSD', function (newValue, oldValue) {
                    if (!oldValue || newValue === oldValue) {
                        return;
                    }
                    $scope.totals.changes.EFBBSD = $scope.format(newValue, oldValue);
                });

                //EFBASD
                $scope.$watch('totals.budget.FB.totals.spentAfterSD', function (newValue, oldValue) {
                    if (!oldValue || newValue === oldValue) {
                        return;
                    }
                    $scope.totals.changes.EFBASD = $scope.format(newValue, oldValue);
                });

                //Savings
                $scope.$watch(' totals.basic.savings', function (newValue, oldValue) {
                    if (!oldValue || newValue === oldValue) {
                        return;
                    }

                    $scope.totals.changes.savings = $scope.format(newValue, oldValue);
                });

                //RB
                $scope.$watch('totals.budget.RB', function (newValue, oldValue) {
                    if (!oldValue || newValue === oldValue) {
                        return;
                    }
                    //$scope.totals.changes.RB.push($scope.format(newValue, oldValue));
                    $scope.totals.changes.RB = $scope.format(newValue, oldValue);
                });

                //RBWEFLB
                $scope.$watch('totals.budget.RBWEFLB', function (newValue, oldValue) {
                    if (!oldValue || newValue === oldValue) {
                        return;
                    }
                    //$scope.totals.changes.RBWEFLB.push($scope.format(newValue, oldValue));
                    $scope.totals.changes.RBWEFLB = $scope.format(newValue, oldValue);
                });

                //Debit
                $scope.$watch('totals.basic.debit', function (newValue, oldValue) {
                    if (!oldValue || newValue === oldValue) {
                        return;
                    }
                    $scope.totals.changes.debit = $scope.format(newValue, oldValue);
                });

                //Balance
                $scope.$watch('totals.basic.balance', function (newValue, oldValue) {
                    if (!oldValue || newValue === oldValue) {
                        return;
                    }
                    $scope.totals.changes.balance = $scope.format(newValue, oldValue);
                });

                //Reconciled
                $scope.$watch('totals.basic.reconciled_sum', function (newValue, oldValue) {
                    if (!oldValue || newValue === oldValue) {
                        return;
                    }
                    $scope.totals.changes.reconciled = $scope.format(newValue, oldValue);
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
                    var $diff = newValue.replace(',', '') - oldValue.replace(',', '');
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

