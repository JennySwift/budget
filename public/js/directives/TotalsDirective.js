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
                "provideFeedback" : "&providefeedback"
            },
            templateUrl: 'templates/TotalsTemplate.php',
            //scope: true,
            link: function($scope, elem, attrs) {
                $scope.filterFactory = FilterFactory;
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
                    $scope.totals.changes.credit = newValue.replace(',', '') - oldValue.replace(',', '');
                });

                //RFB
                $scope.$watch('totals.budget.FB.totals.remaining', function (newValue, oldValue) {
                    if (!oldValue || newValue === oldValue) {
                        return;
                    }
                    $scope.totals.changes.RFB = newValue.replace(',', '') - oldValue.replace(',', '');
                });

                //CFB
                $scope.$watch('totals.budget.FB.totals.cumulative_budget', function (newValue, oldValue) {
                    if (!oldValue || newValue === oldValue) {
                        return;
                    }
                    $scope.totals.changes.CFB = newValue.replace(',', '') - oldValue.replace(',', '');
                });

                //EWB
                $scope.$watch('totals.basic.EWB', function (newValue, oldValue) {
                    if (!oldValue || newValue === oldValue) {
                        return;
                    }
                    $scope.totals.changes.EWB = newValue.replace(',', '') - oldValue.replace(',', '');
                });

                //EFBBSD
                $scope.$watch('totals.budget.FB.totals.spent_before_SD', function (newValue, oldValue) {
                    if (!oldValue || newValue === oldValue) {
                        return;
                    }
                    $scope.totals.changes.EFBBSD = newValue.replace(',', '') - oldValue.replace(',', '');
                });

                //EFBASD
                $scope.$watch('totals.budget.FB.totals.spent_after_SD', function (newValue, oldValue) {
                    if (!oldValue || newValue === oldValue) {
                        return;
                    }
                    $scope.totals.changes.EFBASD = newValue.replace(',', '') - oldValue.replace(',', '');
                });

                //Savings
                $scope.$watch(' totals.basic.savings_total', function (newValue, oldValue) {
                    if (!oldValue || newValue === oldValue) {
                        return;
                    }
                    $scope.totals.changes.savings = newValue.replace(',', '') - oldValue.replace(',', '');
                });

                //RB
                $scope.$watch('totals.budget.RB', function (newValue, oldValue) {
                    if (!oldValue || newValue === oldValue) {
                        return;
                    }
                    $scope.totals.changes.RB.push(newValue.replace(',', '') - oldValue.replace(',', ''));
                });

                //RBWEFLB
                $scope.$watch('totals.budget.RBWEFLB', function (newValue, oldValue) {
                    if (!oldValue || newValue === oldValue) {
                        return;
                    }
                    $scope.totals.changes.RBWEFLB.push(newValue.replace(',', '') - oldValue.replace(',', ''));
                });

                //Debit
                $scope.$watch('totals.basic.debit', function (newValue, oldValue) {
                    if (!oldValue || newValue === oldValue) {
                        return;
                    }
                    $scope.totals.changes.debit = newValue.replace(',', '') - oldValue.replace(',', '');
                });

                //Balance
                $scope.$watch('totals.basic.balance', function (newValue, oldValue) {
                    if (!oldValue || newValue === oldValue) {
                        return;
                    }
                    $scope.totals.changes.balance = newValue.replace(',', '') - oldValue.replace(',', '');
                });

                //Reconciled
                $scope.$watch('totals.basic.reconciled_sum', function (newValue, oldValue) {
                    if (!oldValue || newValue === oldValue) {
                        return;
                    }
                    $scope.totals.changes.reconciled = newValue.replace(',', '') - oldValue.replace(',', '');
                });

                $scope.showSavingsTotalInput = function () {
                    $scope.show.savings_total.input = true;
                    $scope.show.savings_total.edit_btn = false;
                };
            }
        };
    }
}).call(this);

