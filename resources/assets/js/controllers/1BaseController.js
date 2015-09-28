//var app = angular.module('budgetApp', ['checklist-model', 'ngAnimate'], function ($interpolateProvider) {
//    $interpolateProvider.startSymbol('[[');
//    $interpolateProvider.endSymbol(']]');
//});

(function () {

    angular
        .module('budgetApp')
        .controller('BaseController', base);

    function base ($scope, $sce, TotalsFactory, FilterFactory, TransactionsFactory, ShowFactory, ErrorsFactory) {

        $scope.feedback_messages = [];
        $scope.show = ShowFactory.defaults;

        if (typeof env !== 'undefined') {

            if (env === 'local') {
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
                $scope.graphFigures = FilterFactory.calculateGraphFigures($scope.graphTotals);
            }

            $scope.handleAllocationForNewTransaction = function ($transaction) {
                FilterFactory.getTransactions($scope.filter)
                    .then(function (response) {
                        $scope.hideLoading();
                        $scope.transactions = response.data;
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
            $scope.provideFeedback(ErrorsFactory.responseError(response), 'error');
            $scope.hideLoading();
        };

        $scope.closePopup = function ($event, $popup) {
            var $target = $event.target;
            if ($target.className === 'popup-outer') {
                $scope.show.popups[$popup] = false;
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
                $scope.totalsLoading = true;
                TotalsFactory.getSideBarTotals()
                    .then(function (response) {
                        $scope.sideBarTotals = response.data.data;
                        $scope.totalsLoading = false;
                    })
                    .catch(function (response) {
                        $scope.responseError(response);
                    });
            };

            $scope.getSideBarTotals();
        }
    }

})();
