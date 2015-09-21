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
        }

        if (typeof page !== 'undefined' && page === 'home') {
            //Putting this here so that transactions update
            //after inserting transaction from newTransactionController
            $scope.transactions = filter_response.transactions;

            $scope.filter = FilterFactory.filter;
            $scope.filterTotals = filter_response.totals;
            $scope.graphTotals = filter_response.graph_totals;
            $scope.budgets = budgets;

            $scope.filterTransactions = function () {
                $scope.showLoading();
                FilterFactory.filterTransactions($scope.filter)
                    .then(function (response) {
                        $scope.hideLoading();
                        $scope.transactions = response.data.transactions;
                    })
                    .catch(function (response) {
                        $scope.responseError(response);
                    })
            };

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

var app = angular.module('budgetApp');

(function () {

    app.controller('AccountsController', function ($scope, $http, AccountsFactory) {

        $scope.autocomplete = {};
        $scope.edit_account = false;
        $scope.accounts = accounts;
        $scope.edit_account_popup = {};

        $scope.getAccounts = function () {
            $scope.showLoading();
            AccountsFactory.getAccounts()
                .then(function (response) {
                    $scope.accounts = response.data;
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };

        $scope.insertAccount = function ($keycode) {
            if ($keycode !== 13) {
                return;
            }

            $scope.showLoading();
            AccountsFactory.insertAccount()
                .then(function (response) {
                    $scope.getAccounts();
                    $scope.provideFeedback('Account added');
                    $("#new_account_input").val("");
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };

        $scope.showEditAccountPopup = function ($account_id, $account_name) {
            $scope.edit_account_popup.id = $account_id;
            $scope.edit_account_popup.name = $account_name;
            $scope.show.popups.edit_account = true;
        };

        $scope.updateAccount = function () {
            $scope.showLoading();
            AccountsFactory.updateAccountName($scope.edit_account_popup.id, $scope.edit_account_popup.name)
                .then(function (response) {
                    $scope.getAccounts();
                    $scope.provideFeedback('Account edited');
                    $scope.show.popups.edit_account = false;
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };

        $scope.deleteAccount = function ($account) {
            if (confirm("Are you sure you want to delete this account?")) {
                $scope.showLoading();
                AccountsFactory.deleteAccount($account)
                    .then(function (response) {
                        $scope.getAccounts();
                        $scope.provideFeedback('Account deleted');
                        $scope.hideLoading();
                    })
                    .catch(function (response) {
                        $scope.responseError(response);
                    });
            }
        };

    }); //end controller

})();
(function () {

    angular
        .module('budgetApp')
        .controller('BudgetsController', budgets);

    function budgets ($scope, BudgetsFactory, TotalsFactory) {

        $scope.show = {
            newBudget: false,
            popups: {}
        };

        $scope.toggleNewBudget = function () {
            $scope.show.newBudget = true;
        };

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

        else if (page === 'unassignedBudgets') {
            $scope.unassignedBudgetTotals = unassignedBudgetTotals;

            $scope.getUnassignedBudgetTotals = function () {
                $scope.showLoading();
                TotalsFactory.getUnassignedBudgetTotals()
                    .then(function (response) {
                        $scope.unassignedBudgetTotals = response.data;
                        $scope.hideLoading();
                    })
                    .catch(function (response) {
                        $scope.responseError(response);
                    });
            };
        }

        $scope.show.basic_totals = true;
        $scope.show.budget_totals = true;
        $scope.newBudget = {
            type: 'fixed'
        };

        $scope.insertBudget = function ($keycode) {
            if ($keycode !== 13) {
                return;
            }

            var $budget = $scope.newBudget;

            $scope.clearTotalChanges();
            $scope.showLoading();
            $budget.sql_starting_date = $scope.formatDate($budget.starting_date);
            BudgetsFactory.insert($budget)
                .then(function (response) {
                    $scope.jsInsertBudget(response);
                    $scope.getSideBarTotals();
                    $scope.provideFeedback('Budget created');

                    if ($budget.type === 'fixed' && page === 'fixedBudgets') {
                        $scope.getFixedBudgetTotals();
                    }
                    else if ($budget.type === 'flex' && page === 'flexBudgets') {
                        $scope.getFlexBudgetTotals();
                    }
                    else if ($budget.type === 'unassigned' && page === 'unassignedBudgets') {
                        $scope.getUnassignedBudgetTotals();
                    }

                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };

        /**
        * Add the budget to the JS array
        */
        $scope.jsInsertBudget = function (response) {
            var $budget = response.data;
            if ($budget.type === 'fixed' && page === 'fixedBudgets') {
                $scope.fixedBudgets.push($budget);
            }
            else if ($budget.type === 'flex' && page === 'flexBudgets') {
                $scope.flexBudgets.push($budget);
            }
            else if ($budget.type === 'unassigned' && page === 'unassignedBudgets') {
                $scope.unassignedBudgets.push($budget);
            }
        };

        /**
        * For updating budget (name, type, amount, starting date) for an existing budget
        */
        $scope.updateBudget = function () {
            $scope.clearTotalChanges();
            $scope.showLoading();
            $scope.budget_popup.sqlStartingDate = $scope.formatDate($scope.budget_popup.formattedStartingDate);
            BudgetsFactory.update($scope.budget_popup)
                .then(function (response) {
                    $scope.jsUpdateBudget(response);
                    $scope.getSideBarTotals();
                    $scope.show.popups.budget = false;
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };

        $scope.jsUpdateBudget = function (response) {
            //todo: allow for if budget type is changed. I will have to remove the budget from the table it was in
            var $budget = response.data;
            if ($budget.type === 'flex') {
                var $index = _.indexOf($scope.flexBudgets, _.findWhere($scope.flexBudgets, {id: response.data.id}));
                $scope.flexBudgets[$index] = response.data;
            }
            else if ($budget.type === 'fixed') {
                var $index = _.indexOf($scope.fixedBudgets, _.findWhere($scope.fixedBudgets, {id: response.data.id}));
                $scope.fixedBudgets[$index] = response.data;
            }

        };

        $scope.deleteBudget = function ($budget) {
            $scope.showLoading();
            if (confirm('You have ' + $budget.transactionsCount + ' transactions with this budget. Are you sure you want to delete it?')) {
                $scope.showLoading();
                BudgetsFactory.destroy($budget)
                    .then(function (response) {
                        $scope.getSideBarTotals();
                        $scope.jsDeleteBudget($budget);
                        $scope.hideLoading();
                    })
                    .catch(function (response) {
                        $scope.responseError(response);
                    });
            }
            else {
                $scope.hideLoading();
            }
        };

        $scope.jsDeleteBudget = function ($budget) {
            if ($budget.type === 'fixed') {
                var $index = _.indexOf($scope.fixedBudgets, _.findWhere($scope.fixedBudgets, {id: $budget.id}));
                $scope.fixedBudgets = _.without($scope.fixedBudgets, $budget);
            }
            else if ($budget.type === 'flex') {
                var $index = _.indexOf($scope.flexBudgets, _.findWhere($scope.flexBudgets, {id: $budget.id}));
                $scope.flexBudgets = _.without($scope.flexBudgets, $budget);
            }
            else if ($budget.type === 'unassigned') {
                var $index = _.indexOf($scope.unassignedBudgets, _.findWhere($scope.unassignedBudgets, {id: $budget.id}));
                $scope.unassignedBudgets = _.without($scope.unassignedBudgets, $budget);
            }

        };

        $scope.showBudgetPopup = function ($tag, $type) {
            $scope.budget_popup = $tag;
            $scope.budget_popup.type = $type;
            $scope.show.popups.budget = true;
        };

        //$scope.updateSavingsTotal = function ($keycode) {
        //    if ($keycode !== 13) {
        //        return;
        //    }
        //    savings.updatesavingsTotal()
        //        .then(function (response) {
        //            $scope.totals.basic.savings_total = response.data;
        //            $scope.show.savings_total.input = false;
        //            $scope.show.savings_total.edit_btn = true;
        //            $scope.getTotals();
        //        })
        //        .catch(function (response) {
        //            FeedbackFactory.provideFeedback('There was an error');
        //        });
        //};

        //$scope.addFixedToSavings = function ($keycode) {
        //    if ($keycode !== 13) {
        //        return;
        //    }
        //    savings.addFixedToSavings()
        //        .then(function (response) {
        //            $scope.totals.basic.savings_total = response.data;
        //            $scope.getTotals();
        //        })
        //        .catch(function (response) {
        //            FeedbackFactory.provideFeedback('There was an error');
        //        });
        //};

        //$scope.addPercentageToSavings = function ($keycode) {
        //    if ($keycode !== 13) {
        //        return;
        //    }
        //    savings.addPercentageToSavings()
        //        .then(function (response) {
        //            $scope.totals.basic.savings_total = response.data;
        //            $scope.getTotals();
        //        })
        //        .catch(function (response) {
        //            FeedbackFactory.provideFeedback('There was an error');
        //        });
        //};

    }

})();
(function () {

    angular
        .module('budgetApp')
        .controller('FilterController', filter);

    function filter ($scope, FilterFactory) {

        $scope.filterFactory = FilterFactory;
        $scope.accounts = accounts_response;
        $scope.budgets = budgets;
        $scope.types = ["income", "expense", "transfer"];
        $scope.filterTab = 'show';

        $scope.resetFilter = function () {
            FilterFactory.resetFilter();
        };

        /**
         * Watches
         */

        //$scope.$watch('filterFactory.filter_results.graph_totals', function (newValue, oldValue, scope) {
        //    if (newValue) {
        //        //This is running many times when it shouldn't
        //        scope.graph_totals = newValue;
        //        $scope.calculateGraphFigures();
        //    }
        //});

        $scope.$watchCollection('filter.budgets.in.and', function (newValue, oldValue) {
            if (newValue === oldValue) {
                return;
            }
            $scope.filterTransactions();
        });

        $scope.$watchCollection('filter.budgets.in.or', function (newValue, oldValue) {
            if (newValue === oldValue) {
                return;
            }
            $scope.filterTransactions();
        });

        $scope.$watchCollection('filter.budgets.out', function (newValue, oldValue) {
            if (newValue === oldValue) {
                return;
            }
            $scope.filterTransactions();
        });

        $scope.$watchGroup(['filter.offset', 'filter.num_to_fetch'], function (newValue, oldValue) {
            $scope.filter.display_from = $scope.filter.offset + 1;
            $scope.filter.display_to = $scope.filter.offset + ($scope.filter.num_to_fetch * 1);
            if (newValue === oldValue) {
                return;
            }
            $scope.filterTransactions();
        });

        $scope.calculateGraphFigures = function () {
            $scope.graphFigures = {
                months: []
            };

            $($scope.graphTotals.monthsTotals).each(function () {
                var $income = this.income.raw;
                var $expenses = this.expenses.raw * -1;

                var $max = $scope.graphTotals.maxTotal;
                var $num = 500 / $max;

                $scope.graphFigures.months.push({
                    incomeHeight: $income * $num,
                    expensesHeight: $expenses * $num,
                    income: this.income,
                    expenses: this.expenses,
                    month: this.month
                });
            });
        };

        //Todo: I might not need some of this code (not allowing offset to be less than 0)
        // todo: since I disabled the button if that is the case
        $scope.prevResults = function () {
            //make it so the offset cannot be less than 0.
            if ($scope.filter.offset - $scope.filter.num_to_fetch < 0) {
                $scope.filter.offset = 0;
            }
            else {
                $scope.filter.offset-= ($scope.filter.num_to_fetch * 1);
            }
        };

        $scope.nextResults = function () {
            if ($scope.filter.offset + ($scope.filter.num_to_fetch * 1) > $scope.filterTotals.numTransactions) {
                //stop it going past the end.
                return;
            }
            $scope.filter.offset+= ($scope.filter.num_to_fetch * 1);
        };

        $scope.resetSearch = function () {
            $("#search-type-select, #search-account-select, #search-reconciled-select").val("all");
            $("#single-date-input, #from-date-input, #to-date-input, #search-descriptions-input, #search-merchants-input, #search-tags-input").val("");
            $("#search-tag-location").html("");
            $scope.filterTransactions(true);
        };

        $scope.filterDescriptionOrMerchant = function ($keycode) {
            if ($keycode !== 13) {
                return false;
            }
            $scope.resetOffset();
            $scope.filterTransactions(true);
        };

        $scope.filterDate = function ($keycode) {
            if ($keycode !== 13) {
                return false;
            }
            $scope.filterTransactions();
        };

        $scope.filterTotal = function ($keycode) {
            if ($keycode !== 13) {
                return false;
            }
            $scope.filterTransactions();
        };

        /**
         * $type is either 'in' or 'out'
         * @param $field
         * @param $type
         */
        $scope.clearFilterField = function ($field, $type) {
            $scope.filter[$field][$type] = "";
            $scope.filterTransactions();
        };

        /**
         * $type1 is 'in' or 'out'.
         * $type2 is 'and' or 'or'.
         * @param $type1
         * @param $type2
         */
        $scope.clearTagField = function ($type1, $type2) {
            if ($type2) {
                $scope.filter.budgets[$type1][$type2] = [];
            }
            else {
                $scope.filter.budgets[$type1] = [];
            }
        };

        /**
         * $type is either 'in' or 'out'
         * @param $field
         * @param $type
         */
        $scope.clearDateField = function ($field, $type) {
            $scope.filter[$field][$type]['user'] = "";
            $scope.filterTransactions();
        };

        $scope.resetOffset = function () {
            $scope.filter.offset = 0;
        };

        $scope.showContent = function (event) {
            $(event.target).next().addClass('show-me').removeClass('hide');
        };

        $scope.hideContent = function (event) {
            $(event.target).next().addClass('hide-me').removeClass('show');
        };

    }

})();
(function () {

    angular
        .module('budgetApp')
        .controller('FixedBudgetsController', fixedBudgets);

    function fixedBudgets ($scope, TotalsFactory) {


    }

})();
(function () {

    angular
        .module('budgetApp')
        .controller('FlexBudgetsController', flexBudgets);

    function flexBudgets ($scope) {


    }

})();
var app = angular.module('budgetApp');

(function () {

    app.controller('HelpController', function ($scope) {



    }); //end controller

})();
(function () {

    angular
        .module('budgetApp')
        .controller('HomeController', home);

    function home ($scope, TransactionsFactory, PreferencesFactory) {

        $scope.transactionsFactory = TransactionsFactory;
        $scope.page = 'home';

        $scope.colors = me.preferences.colors;

        if ($scope.env === 'local') {
            $scope.tab = 'transactions';
        }
        else {
            $scope.tab = 'transactions';
        }

        $scope.$watch('PreferencesFactory.date_format', function (newValue, oldValue) {
            if (!newValue) {
                return;
            }
            $scope.showLoading();
            PreferencesFactory.insertOrUpdateDateFormat(newValue)
                .then(function (response) {
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        });

        $scope.toggleFilter = function () {
            $scope.show.filter = !$scope.show.filter;
        };

        $scope.transactionsTab = function () {
            $scope.tab = 'transactions';
            $scope.show.basic_totals = true;
            $scope.show.budget_totals = true;
            $scope.show.filter = false;
        };

        $scope.graphsTab = function () {
            $scope.tab = 'graphs';
            $scope.show.basic_totals = false;
            $scope.show.budget_totals = false;
            $scope.show.filter = true;
        };

        if ($scope.tab === 'graphs') {
            $scope.graphsTab();
        }










        /*==============================quick select of transactions==============================*/

        $("body").on('click', '.mass-delete-checkbox-container', function (event) {
            var $this = $(this).closest("tbody");
            var $checked = $(".checked");
            $(".last-checked").removeClass("last-checked");
            $(".first-checked").removeClass("first-checked");

            if (event.shiftKey) {
                var $last_checked = $($checked).last().closest("tbody");
                var $first_checked = $($checked).first().closest("tbody");

                $($last_checked).addClass("last-checked");
                $($first_checked).addClass("first-checked");
                $($this).addClass("checked");

                if ($($this).prevAll(".last-checked").length !== 0) {
                    //$this is after .last-checked
                    shiftSelect("forwards");
                }
                else if ($($this).nextAll(".last-checked").length !== 0) {
                    //$this is before .last-checked
                    shiftSelect("backwards");
                }
            }
            else if (event.altKey) {
                $($this).toggleClass('checked');
            }
            else {
                console.log("no shift");
                $(".checked").not($this).removeClass('checked');
                $($this).toggleClass('checked');
            }
        });

        function shiftSelect ($direction) {
            $("#my_results tbody").each(function () {
                var $prev_checked_length = $(this).prevAll(".checked").length;
                var $after_checked_length = $(this).nextAll(".checked").length;
                var $after_last_checked = $(this).prevAll(".last-checked").length;
                var $before_first_checked = $(this).nextAll(".first-checked").length;

                if ($direction === "forwards") {
                    //if it's after $last_checked and before $this
                    if ($prev_checked_length !== 0 && $after_checked_length !== 0 && $after_last_checked !== 0) {
                        $(this).addClass('checked');
                    }
                }
                else if ($direction === "backwards") {
                    if ($prev_checked_length !== 0 && $after_checked_length !== 0 && $before_first_checked !== 0) {
                        $(this).addClass('checked');
                    }
                }
            });
        }

    }

})();
(function () {

    angular
        .module('budgetApp')
        .controller('NewTransactionController', newTransaction);

    function newTransaction ($scope, TransactionsFactory, FilterFactory) {

        $scope.filterFactory = FilterFactory;
        $scope.dropdown = {};
        $scope.types = ["income", "expense", "transfer"];

        $scope.new_transaction = {
            type: 'income',
            account_id: 1,
            date: {
                entered: 'today'
            },
            merchant: '',
            description: '',
            reconciled: false,
            multiple_budgets: false,
            budgets: []
        };

        /**
         * Fill in the new transaction fields if development environment
         */
        if ($scope.env === 'local') {
            $scope.new_transaction.total = 10;
            $scope.new_transaction.type = 'expense';
            $scope.new_transaction.date.entered = 'today';
            $scope.new_transaction.merchant = 'some merchant';
            $scope.new_transaction.description = 'some description';
            $scope.new_transaction.budgets = [
                {
                    id: '2',
                    name: 'business',
                    type: 'fixed'
                },
                {
                    id: '4',
                    name: 'busking',
                    type: 'flex'
                }
            ];
        }

        $scope.accounts = accounts_response;
        if ($scope.accounts[0]) {
            //this if check is to get rid of the error for a new user who does not yet have any accounts.
            $scope.new_transaction.account_id = $scope.accounts[0].id;
            $scope.new_transaction.from_account_id = $scope.accounts[0].id;
            $scope.new_transaction.to_account_id = $scope.accounts[0].id;
        }

        /**
         * Clear new transaction fields
         */
        function clearNewTransactionFields () {
            if ($scope.env !== 'local') {
                $scope.new_transaction.budgets = [];
            }

            if (me.preferences.clearFields) {
                $scope.new_transaction.total = '';
                $scope.new_transaction.description = '';
                $scope.new_transaction.merchant = '';
                $scope.new_transaction.reconciled = false;
                $scope.new_transaction.multiple_budgets = false;
            }
        }

        /**
         * Return true if there are errors.
         * @returns {boolean}
         */
        function anyErrors () {
            $errorCount = 0;
            var $messages = [];

            if (!Date.parse($scope.new_transaction.date.entered)) {
                $scope.provideFeedback('Date is not valid', 'error');
                $errorCount++;
            }
            else {
                $scope.new_transaction.date.sql = Date.parse($scope.new_transaction.date.entered).toString('yyyy-MM-dd');
            }

            if ($scope.new_transaction.total === "") {
                $scope.provideFeedback('Total is required', 'error');
                $errorCount++;
            }
            else if (!$.isNumeric($scope.new_transaction.total)) {
                $scope.provideFeedback('Total is not a valid number', 'error');
                $errorCount++;
            }

            if ($errorCount > 0) {
                return true;
            }

            return false;
        }

        /**
         * Insert a new transaction
         * @param $keycode
         */
        $scope.insertTransaction = function ($keycode) {
            if ($keycode !== 13 || anyErrors()) {
                return;
            }

            $scope.clearTotalChanges();

            if ($scope.new_transaction.type === 'transfer') {
                insertTransferTransactions();
            }
            else {
                insertIncomeOrExpenseTransaction();
            }
        };

        function insertIncomeOrExpenseTransaction () {
            $scope.showLoading();
            TransactionsFactory.insertIncomeOrExpenseTransaction($scope.new_transaction)
                .then(function (response) {
                    var $transaction = response.data.data;
                    $scope.provideFeedback('Transaction added');
                    clearNewTransactionFields();
                    $scope.new_transaction.dropdown = false;
                    $scope.getSideBarTotals();

                    if ($transaction.multipleBudgets) {
                        $scope.handleAllocationForNewTransaction($transaction);
                    }
                    else {
                        $scope.filterTransactions();
                    }

                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        }

        function insertTransferTransactions () {
            insertTransferTransaction('from');
            setTimeout(function(){
                insertTransferTransaction('to');
            }, 100);
        }

        function insertTransferTransaction ($direction) {
            $scope.showLoading();
            TransactionsFactory.insertTransferTransaction($scope.new_transaction, $direction)
                .then(function (response) {
                    $scope.provideFeedback('Transfer added');
                    clearNewTransactionFields();
                    $scope.getSideBarTotals();
                    $scope.filterTransactions();
                    $scope.new_transaction.dropdown = false;

                    //Todo: get filter stuff
                    //FilterFactory.updateDataForControllers(response.data);
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        }
    }

})();
(function () {

    angular
        .module('budgetApp')
        .controller('PreferencesController', preferences);

    function preferences ($scope, $http, PreferencesFactory, FeedbackFactory) {

        $scope.colors = me.preferences.colors;

        $scope.$watchCollection('colors', function (newValue) {
            $("#income-color-picker").val(newValue.income);
            $("#expense-color-picker").val(newValue.expense);
            $("#transfer-color-picker").val(newValue.transfer);
        });

        $scope.preferences = {};

        $scope.savePreferences = function () {
            PreferencesFactory.savePreferences($scope.me.preferences)
                .then(function (response) {
                    //$scope. = response.data;
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };

        $scope.defaultColor = function ($type, $default_color) {
            if ($type === 'income') {
                $scope.colors.income = $default_color;
            }
            else if ($type === 'expense') {
                $scope.colors.expense = $default_color;
            }
            else if ($type === 'transfer') {
                $scope.colors.transfer = $default_color;
            }
        };
    }

})();
(function () {

    angular
        .module('budgetApp')
        .controller('TransactionsController', transactions);

    function transactions ($scope, TransactionsFactory, FilterFactory) {

        $scope.transactionsFactory = TransactionsFactory;
        $scope.filterFactory = FilterFactory;
        $scope.accounts = accounts_response;

        $scope.updateReconciliation = function ($transaction) {
            $scope.clearTotalChanges();
            $scope.showLoading();
            TransactionsFactory.updateReconciliation($transaction)
                .then(function (response) {
                    $scope.getSideBarTotals();
                    $scope.filterTransactions();
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };

        $scope.updateTransactionSetup = function ($transaction) {
            $scope.edit_transaction = $transaction;
            //save the original total so I can calculate
            // the difference if the total changes,
            // so I can remove the correct amount from savings if required.
            $scope.edit_transaction.original_total = $scope.edit_transaction.total;
            $scope.show.edit_transaction = true;
        };

        $scope.updateTransaction = function () {
            $scope.clearTotalChanges();
            $scope.showLoading();
            TransactionsFactory.updateTransaction($scope.edit_transaction)
                .then(function (response) {
                    $scope.getSideBarTotals();
                    $scope.provideFeedback('Transaction updated');
                    $scope.show.edit_transaction = false;
                    $scope.totals = response.data;
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };

        $scope.fixEditTransactionAccount = function () {
            //$scope.edit_transaction.account wasn't updating with ng-model, so I'm doing it manually.
            $account_id = $("#edit-transaction-account").val();

            $account_match = _.find($scope.accounts, function ($account) {
                return $account.id === $account_id;
            });
            $account_name = $account_match.name;

            $scope.edit_transaction.account.id = $account_id;
            $scope.edit_transaction.account.name = $account_name;
        };

        $scope.massEditTags = function () {
            $scope.showLoading();
            TransactionsFactory.updateMassTags()
                .then(function (response) {
                    multiSearch();
                    $tag_array.length = 0;
                    $tag_location.html($tag_array);
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };

        $scope.massEditDescription = function () {
            $scope.showLoading();
            TransactionsFactory.updateMassDescription()
                .then(function (response) {
                    multiSearch();
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };

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

        $scope.updateAllocationStatus = function () {
            $scope.showLoading();
            TransactionsFactory.updateAllocationStatus($scope.allocationPopup)
                .then(function (response) {
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };

        $scope.deleteTransaction = function ($transaction) {
            if (confirm("Are you sure?")) {
                $scope.clearTotalChanges();
                $scope.showLoading();
                TransactionsFactory.deleteTransaction($transaction, $scope.filter)
                    .then(function (response) {
                        jsDeleteTransaction($transaction);
                        $scope.getSideBarTotals();
                        //Todo: get filter totals with separate request
                        //FilterFactory.updateDataForControllers(response.data);

                        $scope.provideFeedback('Transaction deleted');
                        $scope.hideLoading();
                    })
                    .catch(function (response) {
                        $scope.responseError(response);
                    });
            }
        };

        function jsDeleteTransaction ($transaction) {
          var $index = _.indexOf($scope.transactions, _.findWhere($scope.transactions, {id: $transaction.id}));
            $scope.transactions = _.without($scope.transactions, $scope.transactions[$index]);
        }

        $("#mass-delete-button").on('click', function () {
            if (confirm("You are about to delete " + $(".checked").length + " transactions. Are you sure you want to do this?")) {
                massDelete();
            }
        });

    }

})();
(function () {

    angular
        .module('budgetApp')
        .controller('UnassignedBudgetsController', unassignedBudgets);

    function unassignedBudgets ($scope) {

    }

})();
//# sourceMappingURL=controllers.js.map