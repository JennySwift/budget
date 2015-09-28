var app = angular.module('budgetApp', ['checklist-model', 'ngAnimate'], function ($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});

(function () {

    angular
        .module('budgetApp')
        .controller('BaseController', base);

    function base ($scope, $sce, TotalsFactory, UsersFactory, FilterFactory, TransactionsFactory) {
        /**
         * Scope properties
         */
        $scope.feedback_messages = [];
        $scope.show = {
            popups: {},
            allocationPopup: false,
            actions: false,
            status: false,
            date: true,
            description: true,
            merchant: true,
            total: true,
            type: true,
            account: true,
            reconciled: true,
            tags: true,
            dlt: true,
            //components
            new_transaction: true,
            basic_totals: true,
            budget_totals: true,
            filter_totals: true,
            edit_transaction: false,
            edit_tag: false,
            budget: false,
            filter: false,
            autocomplete: {
                description: false,
                merchant: false
            },
            savings_total: {
                input: false,
                edit_btn: true
            }

        };

        $scope.me = me;

        if (typeof env !== 'undefined') {
            $scope.env = env;

            if ($scope.env === 'local') {
                $scope.tab = 'transactions';
            }
            else {
                $scope.tab = 'transactions';
            }
        }

        if (typeof page !== 'undefined' && page === 'home') {
            //Putting this here so that transactions update
            //after inserting transaction from newTransactionController
            $scope.transactions = transactions;

            $scope.filter = FilterFactory.filter;
            $scope.filterTotals = filterBasicTotals;
            $scope.budgets = budgets;

            $scope.runFilter = function () {
                $scope.getFilterBasicTotals();
                if ($scope.tab === 'transactions') {
                    $scope.filterTransactions();
                }
                else {
                    $scope.getGraphTotals();
                }
            };

            $scope.filterTransactions = function () {
                $scope.showLoading();
                FilterFactory.getTransactions($scope.filter)
                    .then(function (response) {
                        $scope.transactions = response.data;
                        $scope.hideLoading();
                    })
                    .catch(function (response) {
                        $scope.responseError(response);
                    })
            };

            $scope.getFilterBasicTotals = function () {
                FilterFactory.getBasicTotals($scope.filter)
                    .then(function (response) {
                        $scope.filterTotals = response.data;
                        $scope.hideLoading();
                    })
                    .catch(function (response) {
                        $scope.responseError(response);
                    })
            };

            $scope.getGraphTotals = function () {
                FilterFactory.getGraphTotals($scope.filter)
                    .then(function (response) {
                        $scope.graphTotals = response.data;
                        calculateGraphFigures();
                        $scope.hideLoading();
                    })
                    .catch(function (response) {
                        $scope.responseError(response);
                    })
            };

            $scope.resetFilter = function () {
                $scope.filter = FilterFactory.resetFilter();
                $scope.runFilter();
            };

            $scope.transactionsTab = function () {
                $scope.tab = 'transactions';
                $scope.show.basic_totals = true;
                $scope.show.budget_totals = true;
                $scope.show.filter = false;
                $scope.runFilter();
            };

            $scope.graphsTab = function () {
                $scope.tab = 'graphs';
                $scope.show.basic_totals = false;
                $scope.show.budget_totals = false;
                $scope.show.filter = true;
                $scope.runFilter();
            };

            if ($scope.tab === 'graphs') {
                $scope.graphsTab();
            }

            function calculateGraphFigures () {
                $scope.graphFigures = {
                    months: []
                };

                $($scope.graphTotals.monthsTotals).each(function () {
                    var $expenses = this.expenses * -1;
                    var $max = $scope.graphTotals.maxTotal;
                    var $num = 500 / $max;

                    $scope.graphFigures.months.push({
                        incomeHeight: this.income * $num,
                        expensesHeight: $expenses * $num,
                        income: this.income,
                        expenses: this.expenses,
                        month: this.month
                    });
                });
            }

            $scope.handleAllocationForNewTransaction = function ($transaction) {
                FilterFactory.filterTransactions($scope.filter)
                    .then(function (response) {
                        $scope.hideLoading();
                        $scope.transactions = response.data.transactions;
                        var $index = _.indexOf($scope.transactions, _.findWhere($scope.transactions, {id: $transaction.id}));
                        if ($index !== -1) {
                            //The transaction that was just entered is in the filtered transactions
                            $scope.showAllocationPopup($scope.transactions[$index]);
                            //$scope.transactions[$index] = $scope.allocationPopup;
                        }
                        else {
                            $scope.showAllocationPopup($transaction);
                        }
                    })
                    .catch(function (response) {
                        $scope.responseError(response);
                    })
            };

            $scope.showAllocationPopup = function ($transaction) {
                $scope.show.allocationPopup = true;
                $scope.allocationPopup = $transaction;

                $scope.showLoading();
                TransactionsFactory.getAllocationTotals($transaction.id)
                    .then(function (response) {
                        $scope.allocationPopup.totals = response.data;
                        $scope.hideLoading();
                    })
                    .catch(function (response) {
                        $scope.responseError(response);
                    });
            };

            /**
             * This should be in transactions controller but it wasn't firing for some reason
             * @param $keycode
             * @param $type
             * @param $value
             * @param $budget_id
             */
            $scope.updateAllocation = function ($keycode, $type, $value, $budget_id) {
                if ($keycode === 13) {
                    $scope.showLoading();
                    TransactionsFactory.updateAllocation($type, $value, $scope.allocationPopup.id, $budget_id)
                        .then(function (response) {
                            $scope.allocationPopup.budgets = response.data.budgets;
                            $scope.allocationPopup.totals = response.data.totals;
                            $scope.hideLoading();
                        })
                        .catch(function (response) {
                            $scope.responseError(response);
                        });
                }
            };


            /**
             * This should be in transactions controller but it wasn't firing for some reason
             */
            $scope.updateAllocationStatus = function () {
                $scope.showLoading();
                TransactionsFactory.updateAllocationStatus($scope.allocationPopup.id, $scope.allocationPopup.allocated)
                    .then(function (response) {
                        $scope.hideLoading();
                    })
                    .catch(function (response) {
                        $scope.responseError(response);
                    });
            };
        }

        $scope.totalChanges = {};

        $scope.clearTotalChanges = function () {
            $scope.totalChanges = {};
        };

        $(window).load(function () {
            $(".main").css('display', 'block');
            $("footer, #navbar").css('display', 'flex');
            $("#page-loading").hide();
        });

        $scope.showLoading = function () {
            $scope.loading = true;
        };

        $scope.hideLoading = function () {
            $scope.loading = false;
        };

        $scope.provideFeedback = function ($message, $type) {
            var $new = {
                message: $sce.trustAsHtml($message),
                type: $type
            };

            $scope.feedback_messages.push($new);

            //$scope.feedback_messages.push($message);

            setTimeout(function () {
                $scope.feedback_messages = _.without($scope.feedback_messages, $new);
                $scope.$apply();
            }, 3000);
        };

        $scope.responseError = function (response) {
            if(typeof response !== "undefined") {
                switch(response.status) {
                    case 503:
                        $scope.provideFeedback('Sorry, application under construction. Please try again later.', 'error');
                        break;
                    case 401:
                        $scope.provideFeedback('You are not logged in', 'error');
                        break;
                    case 422:
                        var html = "<ul>";
                        angular.forEach(response.data, function(value, key) {
                            var fieldName = key;
                            angular.forEach(value, function(value) {
                                html += '<li>'+value+'</li>';
                            });
                        });
                        html += "</ul>";
                        $scope.provideFeedback(html, 'error');
                        break;
                    default:
                        $scope.provideFeedback(response.data.error, 'error');
                        break;
                }
            }
            else {
                $scope.provideFeedback('There was an error', 'error');
            }

            $scope.hideLoading();
        };

        $scope.closePopup = function ($event, $popup) {
            var $target = $event.target;
            if ($target.className === 'popup-outer') {
                $scope.show.popups[$popup] = false;
            }
        };

        $scope.deleteUser = function () {
            if (confirm("Do you really want to delete your account?")) {
                if (confirm("You are about to delete your account! You will no longer be able to use the budget app. Are you sure this is what you want?")) {
                    $scope.showLoading();
                    UsersFactory.deleteAccount($scope.me)
                        .then(function (response) {
                            //$scope. = response.data;
                            $scope.provideFeedback('Your account has been deleted');
                            $scope.hideLoading();
                        })
                        .catch(function (response) {
                            $scope.responseError(response);
                        });
                }
            }
        };

        $scope.formatDate = function ($date) {
            if ($date) {
                if (!Date.parse($date)) {
                    $scope.provideFeedback('Date is invalid', 'error');
                    return false;
                }
                else {
                    return Date.parse($date).toString('yyyy-MM-dd');
                }
            }
            return false;
        };

        if (typeof page !== 'undefined' && (page === 'home' || page === 'fixedBudgets' || page === 'flexBudgets' || page === 'unassignedBudgets')) {

            $scope.getSideBarTotals = function () {
                $scope.showLoading();
                TotalsFactory.getSideBarTotals()
                    .then(function (response) {
                        $scope.sideBarTotals = response.data.data;
                        $scope.hideLoading();
                    })
                    .catch(function (response) {
                        $scope.responseError(response);
                    });
            };

            $scope.getSideBarTotals();
        }
    }

})();
