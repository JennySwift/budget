var app = angular.module('budgetApp', ['checklist-model', 'ngAnimate'], function ($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});

(function () {

    angular
        .module('budgetApp')
        .controller('BaseController', base);

    function base ($scope, $http, $sce, FeedbackFactory, UsersFactory) {
        /**
         * Scope properties
         */
        $scope.feedback_messages = [];
        $scope.show = {
            popups: {}
        };
        $scope.me = me;

        if (typeof env !== 'undefined') {
            $scope.env = env;
        }

        if (typeof basicTotals !== 'undefined') {
            $scope.basicTotals = basicTotals;
            $scope.fixedBudgetTotals = fixedBudgetTotals;
            $scope.flexBudgetTotals = flexBudgetTotals;
            $scope.remainingBalance = remainingBalance;
        }

        $scope.totalChanges = {};

        $scope.clearTotalChanges = function () {
            $scope.totalChanges = {};
        };

        $scope.updateTotalsAfterResponse = function (response) {
            $scope.basicTotals = response.data.basicTotals;
            $scope.fixedBudgetTotals = response.data.fixedBudgetTotals;
            $scope.flexBudgetTotals = response.data.flexBudgetTotals;
            $scope.remainingBalance = response.data.remainingBalance;
        };

        $(window).load(function () {
            $(".main").css('display', 'block');
            //$("#budget").css('display', 'flex');
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
            //if (response.status === 503) {
            //    $scope.provideFeedback('Sorry, application under construction. Please try again later.', 'error');
            //}
            //else if (response.status === 401) {
            //    $scope.provideFeedback('You are not logged in', 'error');
            //}
            //// Validation errors
            //else if (response.status === 422) {
            //    var html = "<ul>";
            //    angular.forEach(response.data, function(value, key) {
            //        var fieldName = key;
            //        angular.forEach(value, function(value) {
            //            html += '<li>'+value+'</li>';
            //        });
            //    });
            //    html += "</ul>";
            //    $scope.provideFeedback(html, 'error');
            //}
            //else if (response.data.error) {
            //    $scope.provideFeedback(response.data.error, 'error');
            //}
            //else if (response.data) {
            //    //Todo (response.data is in a complicated format)
            //
            //}
            //else {
            //    $scope.provideFeedback('There was an error', 'error');
            //}
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
    }

})();




/*==============================dates==============================*/

$("#convert_date_button_2").on('click', function () {
    $(this).toggleClass("long_date");
    $("#my_results .date").each(function () {
        var $date = $(this).val();
        var $parse = Date.parse($date);
        var $toString;
        if ($("#convert_date_button_2").hasClass("long_date")) {
            $toString = $parse.toString('dd MMM yyyy');
        }
        else {
            $toString = $parse.toString('dd/MM/yyyy');
        }

        $(this).val($toString);
    });
});

/*==============================new month==============================*/

function newMonth () {
    $("#fixed-budget-info-table .spent").each(function () {
        $(this).text(0);
    });
}
var app = angular.module('budgetApp');

(function () {

    app.controller('AccountsController', function ($scope, $http, AccountsFactory, FeedbackFactory) {

        /**
         * scope properties
         */

        //$scope.me = me;
        $scope.autocomplete = {};
        $scope.edit_account = false;
        $scope.accounts = accounts;
        $scope.feedbackFactory = FeedbackFactory;
        $scope.edit_account_popup = {};

        $scope.$watch('feedbackFactory.data', function (newValue, oldValue, scope) {
            if (newValue && newValue.message) {
                scope.provideFeedback(newValue.message);
            }
        });

        /**
         * select
         */

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

        /**
         * insert
         */

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

        /**
         * update
         */

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

        /**
         * delete
         */

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

    function budgets ($scope, $http, BudgetsFactory, FilterFactory, FeedbackFactory) {
        /**
         * scope properties
         */
        $scope.fixedBudgets = fixedBudgets;
        $scope.flexBudgets = flexBudgets;
        $scope.feedbackFactory = FeedbackFactory;

        $scope.show.basic_totals = true;
        $scope.show.budget_totals = true;
        $scope.tab = 'flex';
        $scope.newBudget = {
            type: 'fixed'
        };

        /**
         * Watches
         */

        $scope.$watch('feedbackFactory.data', function (newValue, oldValue, scope) {
            if (newValue && newValue.message) {
                scope.provideFeedback(newValue.message);
            }
        });

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
                    $scope.provideFeedback('Budget created');

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
            if ($budget.type === 'fixed') {
                $scope.fixedBudgets.push($budget);
            }
            else if ($budget.type === 'flex') {
                $scope.flexBudgets.push($budget);
            }
        };

        /**
         * For updating budget (amount, starting date) for an existing budget
         */
        $scope.updateBudget = function () {
            $scope.clearTotalChanges();
            $scope.showLoading();
            $scope.budget_popup.sql_starting_date = $scope.formatDate($scope.budget_popup.formatted_starting_date);
            BudgetsFactory.update($scope.budget_popup, $scope.budget_popup.type)
                .then(function (response) {
                    $scope.updateTotalsAfterResponse(response);
                    //$scope.handleUpdateResponse(response, 'Budget updated');
                    $scope.show.popups.budget = false;
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };

        $scope.handleUpdateResponse = function (response, $message) {
            FilterFactory.updateDataForControllers(response.data);
            $scope.updateTotalsAfterResponse(response);
            $scope.hideLoading();
            $scope.provideFeedback($message);
        };

        $scope.deleteBudget = function ($budget) {
            if (confirm("Are you sure you want to delete this budget?")) {
                $scope.showLoading();
                BudgetsFactory.destroy($budget)
                    .then(function (response) {

                    })
                    .catch(function (response) {
                        $scope.responseError(response);
                    });
            }
        };


        //$scope.updateTag = function ($tag, response) {
        //    var $index = _.indexOf($scope.tags, _.findWhere($scope.tags, {id: $tag.id}));
        //    $scope.tags[$index] = response.data.tag;
        //};

        /**
         * Return true if tag has a budget already
         * @returns {boolean}
         */
        //$scope.tagHasBudget = function ($new) {
        //    if ($new.flex_budget) {
        //        $scope.provideFeedback("You've got a flex budget for that tag.", 'error');
        //        return true;
        //    }
        //    else if ($new.fixed_budget) {
        //        $scope.provideFeedback("You've got a fixed budget for that tag.", 'error');
        //        return true;
        //    }
        //    return false;
        //};

        /**
         * Clear the tag inputs and focus the correct input
         * after entering a new budget
         * todo: clear the budget input
         * @param $type
         */
        //$scope.clearAndFocus = function ($type) {
        //    if ($type === 'fixed') {
        //        //I'm baffled as to why this works to clear the input when the ng-model is new_FB.
        //        //$scope.new_fixed_budget.tag.name = '';
        //
        //        $("#new-fixed-budget-name-input").val("").focus();
        //        $("#new-fixed-budget-SD").val("");
        //        $("#new-fixed-budget-amount").val("");
        //    }
        //    else {
        //        $("#new-flex-budget-name-input").val("").focus();
        //        $("#new-flex-budget-SD").val("");
        //        $("#new-flex-budget-amount").val("");
        //    }
        //};

        //$scope.removeBudget = function ($tag) {
        //    if (confirm("Remove " + $tag.budget_type + " budget for " + $tag.name + "?")) {
        //        $scope.showLoading();
        //        BudgetsFactory.removeBudget($tag)
        //            .then(function (response) {
        //                $scope.updateTotalsAfterResponse(response);
        //                $scope.updateTag($tag, response);
        //                $scope.provideFeedback('Budget deleted');
        //                $scope.hideLoading();
        //            })
        //            .catch(function (response) {
        //                $scope.responseError(response);
        //            });
        //    }
        //};

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

    function filter ($scope, $http, FilterFactory, FeedbackFactory) {

        $scope.something = 'abcdefghijklmn';
        /**
         * scope properties
         */

        $scope.filterFactory = FilterFactory;
        $scope.accounts = accounts_response;
        //$scope.tags = tags_response;
        $scope.types = ["income", "expense", "transfer"];
        $scope.totals = filter_response.totals;
        $scope.filterTab = 'show';
        //$scope.loading = true;

        $scope.resetFilter = function () {
            FilterFactory.resetFilter();
        };

        /**
         * Watches
         */

        // Not sure why I have to do this in the filter controller,
        // but $scope.filter wasn't updating otherwise
        $scope.$watch('filterFactory.filter', function (newValue, oldValue, scope) {
            if (newValue) {
                scope.filter = newValue;

                if (newValue !== oldValue) {
                    $scope.filterTransactions();
                }
            }
        });

        $scope.$watch('filterFactory.filter_results.totals', function (newValue, oldValue, scope) {
            if (newValue) {
                scope.totals = newValue;
                //$scope.calculateGraphFigures();
            }
        });

        $scope.$watch('filterFactory.filter_results.graph_totals', function (newValue, oldValue, scope) {
            if (newValue) {
                //This is running many times when it shouldn't
                scope.graph_totals = newValue;
                $scope.calculateGraphFigures();
            }
        });

        $scope.$watchCollection('filter.tags.in.and', function (newValue, oldValue) {
            if (newValue === oldValue) {
                return;
            }
            $scope.filterTransactions();
        });

        $scope.$watchCollection('filter.tags.in.or', function (newValue, oldValue) {
            if (newValue === oldValue) {
                return;
            }
            $scope.filterTransactions();
        });

        $scope.$watchCollection('filter.tags.out', function (newValue, oldValue) {
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

        /**
         * End watches
         */

        $scope.calculateGraphFigures = function () {
            $scope.graphFigures = {
                months: []
            };
            //console.log($scope.graph_totals);

            $($scope.graph_totals.monthsTotals).each(function () {
                var $income = this.income.raw;
                var $expenses = this.expenses.raw * -1;

                //var $max = Math.max($income, $expenses);
                var $max = $scope.graph_totals.maxTotal;
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

        $scope.filterTransactions = function () {
            $scope.showLoading();
            FilterFactory.filterTransactions($scope.filter)
                .then(function (response) {
                    FilterFactory.updateDataForControllers({filter_results: response.data});
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                })
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
            if ($scope.filter.offset + ($scope.filter.num_to_fetch * 1) > $scope.totals.numTransactions) {
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
                $scope.filter.tags[$type1][$type2] = [];
            }
            else {
                $scope.filter.tags[$type1] = [];
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
var app = angular.module('budgetApp');

(function () {

    app.controller('HelpController', function ($scope, $http, AccountsFactory, FeedbackFactory) {



    }); //end controller

})();
(function () {

    angular
        .module('budgetApp')
        .controller('HomeController', home);

    function home ($scope, $http, BudgetsFactory, TransactionsFactory, PreferencesFactory) {
        /**
         * scope properties
         */

        $scope.test = '3';

        $scope.transactionsFactory = TransactionsFactory;
        $scope.page = 'home';

        $scope.colors = me.preferences.colors;

        if ($scope.env === 'local') {
            $scope.tab = 'transactions';
        }
        else {
            $scope.tab = 'transactions';
        }

        /*=========show=========*/
        $scope.show = {
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
            allocationPopup: false,
            new_transaction_allocation_popup: false,
            savings_total: {
                input: false,
                edit_btn: true
            }
        };

        /**
         * Watches
         */

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

        /**
         * End watches
         */

        /**
         * For trying to get the page load faster,
         * seeing the queries that are taking place
         */
        $scope.debugTotals = function () {
            return $http.get('/test');
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

    function newTransaction ($scope, $http, TransactionsFactory, FilterFactory, FeedbackFactory) {
        /**
         * scope properties
         */

        $scope.filterFactory = FilterFactory;
        $scope.dropdown = {};
        //$scope.tags = tags_response;
        $scope.budgets = budgets;
        $scope.types = ["income", "expense", "transfer"];

        $scope.new_transaction = {
            type: 'income',
            account: 1,
            date: {
                entered: 'today'
            },
            merchant: '',
            description: '',
            reconciled: false,
            multiple_budgets: false,
            tags: []
        };

        /**
         * Fill in the new transaction fields if development environment
         */
        if ($scope.env === 'local') {
            $scope.new_transaction.total = 10;
            $scope.new_transaction.date.entered = 'today';
            $scope.new_transaction.merchant = 'some merchant';
            $scope.new_transaction.description = 'some description';
            $scope.new_transaction.tags = [
                //{
                //    id: '1',
                //    name: 'insurance',
                //    //fixed_budget: '10.00',
                //    //flex_budget: null
                //},
                //{
                //    id: '2',
                //    name: 'petrol',
                //    //fixed_budget: null,
                //    //flex_budget: '5'
                //}
            ];
        }

        $scope.accounts = accounts_response;
        if ($scope.accounts[0]) {
            //this if check is to get rid of the error for a new user who does not yet have any accounts.
            $scope.new_transaction.account = $scope.accounts[0].id;
            $scope.new_transaction.from_account = $scope.accounts[0].id;
            $scope.new_transaction.to_account = $scope.accounts[0].id;
        }

        /**
         * Watches
         */

        $scope.$watch('filterFactory.filter', function (newValue, oldValue, scope) {
            if (newValue) {
                scope.filter = newValue;
            }
        });

        /**
         * Clear new transaction fields
         */
        $scope.clearNewTransactionFields = function () {
            $scope.new_transaction.tags = [];

            if (me.preferences.clearFields) {
                $scope.new_transaction.total = '';
                $scope.new_transaction.description = '';
                $scope.new_transaction.merchant = '';
                $scope.new_transaction.reconciled = false;
                $scope.new_transaction.multiple_budgets = false;
            }
        };

        /**
         * Return true if there are errors.
         * @returns {boolean}
         */
        $scope.anyErrors = function () {
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
        };

        /**
         * Insert a new transaction
         * @param $keycode
         */
        $scope.insertTransaction = function ($keycode) {
            if ($keycode !== 13 || $scope.anyErrors()) {
                return;
            }

            $scope.clearTotalChanges();
            $scope.showLoading();
            TransactionsFactory.insertTransaction($scope.new_transaction, $scope.filter)
                .then(function (response) {
                    $scope.provideFeedback('Transaction added');
                    $scope.clearNewTransactionFields();
                    $scope.new_transaction.dropdown = false;
                    FilterFactory.updateDataForControllers(response.data);
                    $scope.updateTotalsAfterResponse(response);
                    $scope.checkNewTransactionForMultipleBudgets(response);
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };

        /**
         * See if the transaction that was just entered has multiple budgets.
         * @param response
         */
        $scope.checkNewTransactionForMultipleBudgets = function (response) {
            if (response.data.multiple_budgets) {
                $scope.allocation_popup = response.data.transaction;
                $scope.showAllocationPopupForNewTransaction();
            }
        };

        $scope.showAllocationPopupForNewTransaction = function () {
            var $transaction = $scope.findTransaction();

            if ($transaction) {
                $scope.showAllocationPopup($transaction);
            }
            else {
                //the transaction isn't showing with the current filter settings
                $scope.showAllocationPopup($scope.allocation_popup);
            }
        };

        /**
         * For the allocation popup when a new transaction is entered.
         * Find the transaction that was just entered.
         * This is so that the transaction is updated live
         * when actions are done in the allocation popup.
         * Otherwise it will need a page refresh.
         */
        $scope.findTransaction = function () {
            var $transaction = _.find(FilterFactory.filter_results.transactions, function ($scope_transaction) {
                return $scope_transaction.id === $scope.allocation_popup.id;
            });

            return $transaction;
        };
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

        /**
         * scope properties
         */

        $scope.preferences = {};

        $scope.responseError = function (response) {
            if (response.status === 503) {
                FeedbackFactory.provideFeedback('Sorry, application under construction. Please try again later.');
            }
            else {
                FeedbackFactory.provideFeedback('There was an error');
            }
        };

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
        .controller('TotalsController', totals);

    function totals ($scope, $http) {

    }

})();
(function () {

    angular
        .module('budgetApp')
        .controller('TransactionsController', transactions);

    function transactions ($scope, $http, TransactionsFactory, FilterFactory) {
        /**
         * Scope properties
         */

        $scope.transactionsFactory = TransactionsFactory;
        $scope.filterFactory = FilterFactory;
        $scope.transactions = filter_response.transactions;
        $scope.accounts = accounts_response;

        /**
         * Watches
         */

        $scope.$watch('filterFactory.filter', function (newValue, oldValue, scope) {
            if (newValue) {
                scope.filter = newValue;
            }
        });

        $scope.$watch('filterFactory.filter_results.transactions', function (newValue, oldValue, scope) {
            if (newValue) {
                scope.transactions = newValue;
            }
        });

        $scope.updateReconciliation = function ($transaction_id, $reconciliation) {
            $scope.clearTotalChanges();
            $scope.showLoading();
            TransactionsFactory.updateReconciliation($transaction_id, $reconciliation, $scope.filter)
                .then(function (response) {
                    FilterFactory.updateDataForControllers(response.data);
                    $scope.updateTotalsAfterResponse(response);
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
            var $date_entry = $("#edit-transaction-date").val();
            $scope.edit_transaction.date.user = $date_entry;
            $scope.edit_transaction.date.sql = Date.parse($date_entry).toString('yyyy-MM-dd');
            $scope.clearTotalChanges();
            $scope.showLoading();
            TransactionsFactory.updateTransaction($scope.edit_transaction, $scope.filter)
                .then(function (response) {
                    FilterFactory.updateDataForControllers(response.data);
                    $scope.updateTotalsAfterResponse(response);
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

        /**
         * For after the response in $scope.updateAllocation, if I want to just update
         * one tag. I switched to updating all tags so I could do the automatic allocation
         * of the other tags to 0% when one is changed to 100%, so I am not using this function anymore.
         * @param $tag_id
         */
        $scope.updateTagAllocation = function ($tag_id) {
            //find the tag in $scope.allocationPopup.budgets
            var $the_tag = _.find($scope.allocationPopup.budgets, function ($tag) {
                return $tag.id === $tag_id;
            });
            //get the index of the tag in $scope.allocationPopup_transaction.tags
            var $index = _.indexOf($scope.allocationPopup.budgets, $the_tag);
            //make the tag equal the ajax response
            $scope.allocationPopup.budgets[$index] = response.data.allocation_info;
        };

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

        $scope.deleteTransaction = function ($transaction) {
            if (confirm("Are you sure?")) {
                $scope.clearTotalChanges();
                $scope.showLoading();
                TransactionsFactory.deleteTransaction($transaction, $scope.filter)
                    .then(function (response) {
                        FilterFactory.updateDataForControllers(response.data);
                        $scope.updateTotalsAfterResponse(response);

                        $scope.provideFeedback('Transaction deleted');
                        $scope.hideLoading();
                    })
                    .catch(function (response) {
                        $scope.responseError(response);
                    });
            }
        };

        $("#mass-delete-button").on('click', function () {
            if (confirm("You are about to delete " + $(".checked").length + " transactions. Are you sure you want to do this?")) {
                massDelete();
            }
        });

    }

})();
//# sourceMappingURL=controllers.js.map