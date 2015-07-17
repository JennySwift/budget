;(function(){
    'use strict';
    angular
        .module('budgetApp')
        .directive('totalsDirective', totals);

    /* @inject */
    function totals() {
        return {
            restrict: 'EA',
            scope: {
                //"id": "@id",
                "totals": "=totals"
            },
            templateUrl: 'templates/TotalsTemplate.php',
            //scope: true,
            link: function($scope, elem, attrs) {
                $scope.show = {
                    basic_totals: true,
                    budget_totals: true
                };

                $scope.totals.changes = {
                    RB: [],
                    RBWEFLB: []
                };

                $scope.clearChanges = function () {
                    $scope.totals.changes = {
                        RB: [],
                        RBWEFLB: []
                    };
                };

                /**
                 * Notify user when totals change
                 */

                //Credit
                $scope.$watch('totals.basic.credit', function (newValue, oldValue) {
                    if (!oldValue || newValue === oldValue) {
                        return;
                    }
                    $scope.totals.changes.credit = newValue.replace(',', '') - oldValue.replace(',', '');
                });

                //RFB
                $scope.$watch('totals.budget.FB.totals.remaining', function (newValue, oldValue) {
                    if (!oldValue || newValue === oldValue) {
                        return;
                    }
                    $scope.totals.changes.RFB = newValue - oldValue;
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

                //RBWEFLB
                $scope.$watch('totals.budget.RBWEFLB', function (newValue, oldValue) {
                    if (!oldValue || newValue === oldValue) {
                        return;
                    }
                    $scope.totals.changes.RBWEFLB.push(newValue - oldValue);
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
            }
        };
    }
}).call(this);

