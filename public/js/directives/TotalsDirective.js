;(function(){
    'use strict';
    angular
        .module('budgetApp')
        .directive('totalsDirective', totals);

    /* @inject */
    function totals(SavingsFactory, TotalsFactory, FilterFactory) {
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
                $scope.$watch('totals.basic.expense_without_budget_total', function (newValue, oldValue) {
                    if (!oldValue || newValue === oldValue) {
                        return;
                    }
                    $scope.totals.changes.EWB = newValue.replace(',', '') - oldValue.replace(',', '');
                });

                //EFLB
                $scope.$watch('totals.basic.EFLB', function (newValue, oldValue) {
                    if (!oldValue || newValue === oldValue) {
                        return;
                    }
                    $scope.totals.changes.EFLB = newValue.replace(',', '') - oldValue.replace(',', '');
                });

                //EFBBCSD
                $scope.$watch('totals.budget.FB.totals.spent_before_CSD', function (newValue, oldValue) {
                    if (!oldValue || newValue === oldValue) {
                        return;
                    }
                    $scope.totals.changes.EFBBCSD = newValue.replace(',', '') - oldValue.replace(',', '');
                });

                //EFBACSD
                $scope.$watch('totals.budget.FB.totals.spent', function (newValue, oldValue) {
                    if (!oldValue || newValue === oldValue) {
                        return;
                    }
                    $scope.totals.changes.EFBACSD = newValue.replace(',', '') - oldValue.replace(',', '');
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

                $scope.addPercentageToSavingsAutomatically = function ($amount_to_add) {
                    SavingsFactory.addPercentageToSavingsAutomatically($amount_to_add)
                        .then(function (response) {
                            $scope.totals.basic = response.data.basic;
                            $scope.totals.budget = response.data.budget;
                        })
                        .catch(function (response) {
                            $scope.provideFeedback('There was an error');
                        });
                };

                $scope.reverseAutomaticInsertIntoSavings = function ($amount_to_subtract) {
                    SavingsFactory.reverseAutomaticInsertIntoSavings($amount_to_subtract)
                        .then(function (response) {
                            $scope.totals.basic = response.data.basic;
                            $scope.totals.budget = response.data.budget;
                        })
                        .catch(function (response) {
                            $scope.provideFeedback('There was an error');
                        });
                };
            }
        };
    }
}).call(this);

