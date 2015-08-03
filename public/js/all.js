var app = angular.module('budgetApp', ['checklist-model', 'ngAnimate'], function ($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});

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
(function () {

    angular
        .module('budgetApp')
        .controller('HomeController', home);

    function home ($scope, $http, BudgetsFactory, TransactionsFactory, PreferencesFactory, FeedbackFactory, ColorsFactory) {
        /**
         * scope properties
         */

        $scope.feedbackFactory = FeedbackFactory;
        $scope.transactionsFactory = TransactionsFactory;
        $scope.feedback_messages = [];
        $scope.page = 'home';

        $scope.colors = colors_response;
        $scope.totals = totals_response;
        $scope.me = me;
        //$scope.loading = true;

        $(window).load(function () {
            $(".main").css('display', 'block');
            $("footer, #navbar").css('display', 'flex');
            $("#page-loading").hide();
        });

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
            // modals
            color_picker: false,
            //components
            new_transaction: true,
            basic_totals: true,
            budget_totals: true,
            filter_totals: true,
            edit_transaction: false,
            edit_tag: false,
            edit_CSD: false,
            filter: true,
            autocomplete: {
                description: false,
                merchant: false
            },
            allocation_popup: false,
            new_transaction_allocation_popup: false,
            savings_total: {
                input: false,
                edit_btn: true
            }
        };

        /**
         * Watches
         */

        $scope.$watch('feedbackFactory.data', function (newValue, oldValue, scope) {
            if (newValue && newValue.message) {
                scope.provideFeedback(newValue.message);
            }
        });

        $scope.$watch('PreferencesFactory.date_format', function (newValue, oldValue) {
            if (!newValue) {
                return;
            }
            PreferencesFactory.insertOrUpdateDateFormat(newValue).then(function (response) {
                // $scope. = response.data;
            });
        });

        $scope.$watchCollection('colors', function (newValue) {
            $("#income-color-picker").val(newValue.income);
            $("#expense-color-picker").val(newValue.expense);
            $("#transfer-color-picker").val(newValue.transfer);
        });

        /**
         * End watches
         */

        $scope.showLoading = function () {
            $scope.loading = true;
        };

        $scope.hideLoading = function () {
            $scope.loading = false;
        };

        $scope.provideFeedback = function ($message) {
            $scope.feedback_messages.push($message);
            setTimeout(function () {
                $scope.feedback_messages = _.without($scope.feedback_messages, $message);
                $scope.$apply();
            }, 3000);
        };

        $scope.responseError = function (response) {
            if (response.status === 503) {
                FeedbackFactory.provideFeedback('Sorry, application under construction. Please try again later.');
            }
            else {
                FeedbackFactory.provideFeedback('There was an error');
            }
        };

        /**
         * For trying to get the page load faster,
         * seeing the queries that are taking place
         */
        $scope.debugTotals = function () {
            return $http.get('/test');
        };

        $scope.testFeedback = function () {
            $scope.provideFeedback('something');
        };

        $scope.updateColors = function () {
            ColorsFactory.updateColors($scope.colors)
                .then(function (response) {
                    //Todo: return the colors in the response to update them
                    $scope.show.color_picker = false;
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

        // =================================allocation=================================

        $scope.showAllocationPopup = function ($transaction) {
            $scope.show.allocation_popup = true;
            $scope.allocation_popup = $transaction;

            BudgetsFactory.getAllocationTotals($transaction.id)
                .then(function (response) {
                    $scope.allocation_popup.allocation_totals = response.data;
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };

        $scope.updateChart = function () {
            $(".bar_chart_li:first-child").css('height', '0%');
            $(".bar_chart_li:nth-child(2)").css('height', '0%');
            $(".bar_chart_li:first-child").css('height', getTotal()[6] + '%');
            $(".bar_chart_li:nth-child(2)").css('height', getTotal()[5] + '%');
        };

        $scope.toggleFilter = function () {
            $scope.show.filter = !$scope.show.filter;
        };

        $scope.closePopup = function ($event, $popup) {
            var $target = $event.target;
            if ($target.className === 'popup-outer') {
                $scope.show.popups[$popup] = false;
            }
        };












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
        .controller('FilterController', filter);

    function filter ($scope, $http, FilterFactory, FeedbackFactory) {
        /**
         * scope properties
         */

        $scope.filterFactory = FilterFactory;
        $scope.accounts = accounts_response;
        $scope.tags = tags_response;
        $scope.types = ["income", "expense", "transfer"];
        $scope.totals = filter_response.totals;
        $scope.filterTab = 'show';
        //$scope.loading = true;

        //console.log($scope.loading);

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
            }
        });

        $scope.$watch('filterFactory.filter_results.totals', function (newValue, oldValue, scope) {
            if (newValue) {
                scope.totals = newValue;
            }
        });

        $scope.$watchCollection('filter.tags', function (newValue, oldValue) {
            if (newValue === oldValue) {
                return;
            }
            $scope.multiSearch(true);
        });

        $scope.$watchGroup(['filter.offset', 'filter.num_to_fetch'], function (newValue, oldValue) {
            $scope.filter.display_from = $scope.filter.offset + 1;
            $scope.filter.display_to = $scope.filter.offset + ($scope.filter.num_to_fetch * 1);
            if (newValue === oldValue) {
                return;
            }
            $scope.multiSearch(true);
        });

        /**
         * End watches
         */

        $scope.responseError = function (response) {
            if (response.status === 503) {
                FeedbackFactory.provideFeedback('Sorry, application under construction. Please try again later.');
            }
            else {
                FeedbackFactory.provideFeedback('There was an error');
            }
        };

        $scope.multiSearch = function () {
            $scope.showLoading();
            FilterFactory.multiSearch($scope.filter)
                .then(function (response) {
                    FilterFactory.updateDataForControllers({filter_results: response.data});
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                    $scope.hideLoading();
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
            if ($scope.filter.offset + ($scope.filter.num_to_fetch * 1) > $scope.totals.num_transactions) {
                //stop it going past the end.
                return;
            }
            $scope.filter.offset+= ($scope.filter.num_to_fetch * 1);
        };

        $scope.resetSearch = function () {
            $("#search-type-select, #search-account-select, #search-reconciled-select").val("all");
            $("#single-date-input, #from-date-input, #to-date-input, #search-descriptions-input, #search-merchants-input, #search-tags-input").val("");
            $("#search-tag-location").html("");
            $scope.multiSearch(true);
        };

        //$(".clear-search-button").on('click', function () {
        //    if ($(this).attr('id') == "clear-tags-btn") {
        //        $search_tag_array.length = 0;
        //        $("#search-tag-location").html($search_tag_array);
        //    }
        //    $(this).closest(".input-group").children("input").val("");
        //    $scope.multiSearch(true);
        //});

        //$("#search-div").on('click', '#search-tag-location li', function () {
        //    removeTag(this, $search_tag_array, $("#search-tag-location"), multiSearch);
        //});

        $scope.filterDescriptionOrMerchant = function ($keycode) {
            if ($keycode !== 13) {
                return false;
            }
            $scope.resetOffset();
            $scope.multiSearch(true);
        };

        $scope.filterDate = function ($keycode) {
            if ($keycode !== 13) {
                return false;
            }
            $scope.multiSearch();
        };

        $scope.filterTotal = function ($keycode) {
            if ($keycode !== 13) {
                return false;
            }
            $scope.multiSearch();
        };

        /**
         * Needed for filter
         * @param $keycode
         * @param $func
         * @param $params
         */
        //$scope.checkKeycode = function ($keycode, $func, $params) {
        //    if ($keycode === 13) {
        //        $func($params);
        //    }
        //};

        $scope.clearFilterField = function ($field) {
            if ($field === 'tags') {
                $scope.filter.tags = [];
            }
            else {
                $scope.filter[$field] = "";
                $scope.multiSearch();
            }
        };

        $scope.resetOffset = function () {
            $scope.filter.offset = 0;
        };

        $scope.showContent = function (event) {
            $(event.target).next().addClass('show-me').removeClass('hide');
            //$(event.target).next().addClass('animated slideInDown').removeClass('hide');
        };

        $scope.hideContent = function (event) {
            $(event.target).next().addClass('hide-me').removeClass('show');
            //$(event.target).next().addClass('animated slideOutUp').removeClass('show');
        };


    }

})();
(function () {

    angular
        .module('budgetApp')
        .controller('TransactionsController', transactions);

    function transactions ($scope, $http, TransactionsFactory, FilterFactory, BudgetsFactory, FeedbackFactory) {
        /**
         * Scope properties
         */

        $scope.transactionsFactory = TransactionsFactory;
        $scope.filterFactory = FilterFactory;
        $scope.transactions = filter_response.transactions;
        $scope.tags = tags_response;
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

        $scope.responseError = function (response) {
            if (response.status === 503) {
                FeedbackFactory.provideFeedback('Sorry, application under construction. Please try again later.');
            }
            else {
                FeedbackFactory.provideFeedback('There was an error');
            }
        };

        $scope.updateReconciliation = function ($transaction_id, $reconciliation) {
            TransactionsFactory.updateReconciliation($transaction_id, $reconciliation, $scope.filter)
                .then(function (response) {
                    FilterFactory.updateDataForControllers(response.data);
                    $scope.totals = response.data;
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
            TransactionsFactory.updateTransaction($scope.edit_transaction, $scope.filter)
                .then(function (response) {
                    FilterFactory.updateDataForControllers(response.data);
                    $scope.provideFeedback('Transaction updated');

                    $scope.show.edit_transaction = false;

                    $scope.totals = response.data;
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
            TransactionsFactory.updateMassTags()
                .then(function (response) {
                    multiSearch();
                    $tag_array.length = 0;
                    $tag_location.html($tag_array);
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };

        $scope.massEditDescription = function () {
            TransactionsFactory.updateMassDescription()
                .then(function (response) {
                    multiSearch();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };

        $scope.updateAllocation = function ($keycode, $type, $value, $tag_id) {
            if ($keycode === 13) {
                BudgetsFactory.updateAllocation($type, $value, $scope.allocation_popup.id, $tag_id)
                    .then(function (response) {
                        //find the tag in $scope.allocation_popup.tags
                        var $the_tag = _.find($scope.allocation_popup.tags, function ($tag) {
                            return $tag.id === $tag_id;
                        });
                        //get the index of the tag in $scope.allocation_popup_transaction.tags
                        var $index = _.indexOf($scope.allocation_popup.tags, $the_tag);
                        //make the tag equal the ajax response
                        $scope.allocation_popup.tags[$index] = response.data.allocation_info;
                        $scope.allocation_popup.allocation_totals = response.data.allocation_totals;
                    })
                    .catch(function (response) {
                        $scope.responseError(response);
                    });
            }
        };

        $scope.updateAllocationStatus = function () {
            BudgetsFactory.updateAllocationStatus($scope.allocation_popup.id, $scope.allocation_popup.allocated)
                .then(function (response) {
                    console.log("something");
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };

        $scope.deleteTransaction = function ($transaction) {
            if (confirm("Are you sure?")) {
                TransactionsFactory.deleteTransaction($transaction, $scope.filter)
                    .then(function (response) {
                        $scope.totals = response.data.totals;
                        //$scope.calculateAmountToTakeFromSavings($transaction);

                        FilterFactory.updateDataForControllers(response.data);

                        $scope.provideFeedback('Transaction deleted');
                    })
                    .catch(function (response) {
                        $scope.responseError(response);
                    });
            }
        };

        //$scope.calculateAmountToTakeFromSavings = function ($transaction) {
        //    //reverse the automatic insertion into savings if it is an income expense
        //    if ($transaction.type === 'income') {
        //        //This value will change. Just for developing purposes.
        //        var $percent = 10;
        //        var $amount_to_subtract = $transaction.total / 100 * $percent;
        //        $scope.reverseAutomaticInsertIntoSavings($amount_to_subtract);
        //    }
        //};

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
        .controller('NewTransactionController', newTransaction);

    function newTransaction ($scope, $http, TransactionsFactory, FilterFactory, FeedbackFactory) {
        /**
         * scope properties
         */

        $scope.filterFactory = FilterFactory;
        $scope.dropdown = {};
        $scope.me = me;
        $scope.env = env;
        $scope.tags = tags_response;
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

        $scope.responseError = function (response) {
            if (response.status === 503) {
                FeedbackFactory.provideFeedback('Sorry, application under construction. Please try again later.');
            }
            else {
                FeedbackFactory.provideFeedback('There was an error');
            }
        };

        /**
         * Clear new transaction fields
         */
        $scope.clearNewTransactionFields = function () {
            $scope.new_transaction.tags = [];

            if (me.settings.clear_fields) {
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
                FeedbackFactory.provideFeedback('Date is not valid');
                $errorCount++;
            }
            else {
                $scope.new_transaction.date.sql = Date.parse($scope.new_transaction.date.entered).toString('yyyy-MM-dd');
            }

            if ($scope.new_transaction.total === "") {
                FeedbackFactory.provideFeedback('Total is required');
                $errorCount++;
            }
            else if (!$.isNumeric($scope.new_transaction.total)) {
                FeedbackFactory.provideFeedback('Total is not a valid number');
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

            TransactionsFactory.insertTransaction($scope.new_transaction, $scope.filter)
                .then(function (response) {
                    $scope.provideFeedback('Transaction added');
                    $scope.clearNewTransactionFields();
                    $scope.new_transaction.dropdown = false;
                    FilterFactory.updateDataForControllers(response.data);
                    $scope.checkNewTransactionForMultipleBudgets(response);
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
            PreferencesFactory.savePreferences($scope.me.settings)
                .then(function (response) {
                    //$scope. = response.data;
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };

    }

})();
app.factory('AutocompleteFactory', function ($http) {
	var $object = {};

	$object.duplicateCheck = function ($this, $transactions_without_duplicates) {
		var $duplicate_counter = 0;
		$($transactions_without_duplicates).each(function () {
			if ($this.description === this.description && $this.merchant === this.merchant && $this.total === this.total && $this.type === this.type && $this.account === this.account) {
				//it is a duplicate
				$duplicate_counter += 1;
			}
		});
		if ($duplicate_counter === 0) {
			var $allocated_percent = $this.tags.allocated_percent;
			var $allocated_fixed = null;
			var $amount = $this.tags.amount;

			if ($allocated_percent === null) {
				$allocated_fixed = $amount;
			}
			$transactions_without_duplicates.push($this);
		}
	};
	$object.removeDuplicates = function ($transactions) {
		//for the transaction autocomplete
		for (var i = 0; i < $transactions.length; i++) {
			var $transaction = $transactions[i];
			var $id = $transaction.id;
			var $description = $transaction.description;
			var $merchant = $transaction.merchant;
			var $total = $transaction.total;
			var $type = $transaction.type;
			var $account = $transaction.account;
			var $from_account = $transaction.from_account;
			var $to_account = $transaction.to_account;

			var $object_1;

			if ($type === 'transfer') {
				$object_1 = {
					description: $description,
					total: $total,
					from_account: $from_account,
					to_account: $to_account
				};
			}
			else {
				$object_1 = {
					description: $description,
					merchant: $merchant,
					total: $total,
					type: $type,
					account: $account
				};
			}

			//we have the properties that we don't want to be duplicates in an object. now we loop through the array again to make another object, then we can compare if the two objects are equal.
			for (var j = 0; j < $transactions.length; j++) {
				var $t = $transactions[j];
				var $index = $transactions.indexOf($t);
				var $t_id = $t.id;
				var $t_description = $t.description;
				var $t_merchant = $t.merchant;
				var $t_total = $t.total;
				var $t_type = $t.type;
				var $t_account = $t.account;
				var $t_from_account = $t.from_account;
				var $t_to_account = $t.to_account;

				var $object_2 = {};

				if ($t_id !== $id && $t_type === $type) {
					//they are the same type, and not the same transaction
					if ($type === 'transfer') {
						$object_2 = {
							description: $t_description,
							total: $t_total,
							from_account: $t_from_account,
							to_account: $t_to_account
						};
					}
					else {
						$object_2 = {
							description: $t_description,
							merchant: $t_merchant,
							total: $t_total,
							type: $t_type,
							account: $t_account
						};
					}
				}

				if (_.isEqual($object_1, $object_2)) {
					$transactions.splice($index, 1);
				}				
			}
		}
		
		return $transactions;
	};
	$object.transferTransactions = function ($transactions) {
		var $counter = 0;
		var $from_account;
		var $to_account;
		var $total;
		
		$($transactions).each(function () {
			var $index = $transactions.indexOf(this);
			if (this.type === "transfer") {
				$counter++;
				if (this.total.indexOf("-") != -1) {
					//this is a negative transfer
					$from_account = this.account;
				}
				else if (this.total.indexOf("-") == -1) {
					//this is a positive transfer
					$to_account = this.account;
					$total = this.total;
				}
				if ($counter % 2 === 1) {
					//remove every second transfer transaction from the array
					$transactions.splice($index, 1);
				}
				else if ($counter % 2 === 0) {
					//keep the first of every second transfer transaction and combine the two transfers into one
					this.from_account = $from_account;
					this.to_account = $to_account;
					this.account = {};
					//so the total is positive
					this.total = $total;
				}
			}
		});
		return $transactions;
	};
	return $object;
});
app.factory('BudgetsFactory', function ($http) {
	return {
		getAllocationTotals: function ($transaction_id) {
			var $url = 'select/allocationTotals';
			var $data = {
				transaction_id: $transaction_id
			};
			
			return $http.post($url, $data);
		},
		
		updateBudget: function ($tag, $column, $budget) {
			var $url = 'update/budget';
			var $data = {
				tag_id: $tag.id,
				column: $column,
				budget: $budget
			};
			
			return $http.post($url, $data);
		},

		updateAllocation: function ($type, $value, $transaction_id, $tag_id) {
			var $url = 'update/allocation';
			var $description = 'allocation';
			var $data = {
				description: $description,
				type: $type,
				value: $value,
				transaction_id: $transaction_id,
				tag_id: $tag_id
			};
			
			return $http.post($url, $data);
		},
		updateAllocationStatus: function ($transaction_id, $status) {
			var $url = 'update/allocationStatus';
			var $data = {
				transaction_id: $transaction_id,
				status: $status
			};
			
			return $http.post($url, $data);
		},
		updateCSD: function ($tag) {
            var $url = $tag.path;

            var $data = {
                tag: $tag,
                CSD: Date.parse($tag.CSD).toString('yyyy-MM-dd')
            };
            
            return $http.put($url, $data);
		}
	};
});
app.factory('SavingsFactory', function ($http) {
	return {
		updateSavingsTotal: function () {
			var $amount = $("#edited-savings-total").val().replace(',', '');
			var $url = 'update/savingsTotal';
			var $data = {
				amount: $amount
			};
			
			return $http.post($url, $data);
		},
		addFixedToSavings: function () {
			var $amount_to_add = $("#add-fixed-to-savings").val();
			var $url = 'update/addFixedToSavings';
			var $data = {
				amount_to_add: $amount_to_add
			};
			$("#add-fixed-to-savings").val("");
			
			return $http.post($url, $data);
		},
		addPercentageToSavings: function () {
			var $percentage_of_RB = $("#add-percentage-to-savings").val();
			var $url = 'update/addPercentageToSavings';
			var $data = {
				percentage_of_RB: $percentage_of_RB,
			};
			$("#add-percentage-to-savings").val("");
			
			return $http.post($url, $data);
		},
		addPercentageToSavingsAutomatically: function ($amount_to_add) {
			var $url = 'update/addPercentageToSavingsAutomatically';
			var $data = {
				amount_to_add: $amount_to_add
			};
			
			return $http.post($url, $data);
		},
		reverseAutomaticInsertIntoSavings: function ($amount_to_subtract) {
			var $url = 'update/reverseAutomaticInsertIntoSavings';
			var $data = {
				amount_to_subtract: $amount_to_subtract
			};
			
			return $http.post($url, $data);
		}
	};
});
app.factory('ColorsFactory', function ($http) {
	return {
		updateColors: function ($colors) {
			var $url = 'update/colors';
			var $description = 'colors';
			var $data = {
				description: $description,
				colors: $colors
			};
			
			return $http.post($url, $data);
		}
	};
});

app.factory('TransactionsFactory', function ($http) {
    var $object = {};
    $object.totals = {};

    $object.insertTransaction = function ($new_transaction, $filter) {
        var $url = 'insert/transaction';
        var $description = 'new transaction';

        if ($new_transaction.type === "expense" && $new_transaction.total > 0) {
            //transaction is an expense without the negative sign
            $new_transaction.total = $new_transaction.total * -1;
        }

        else if ($new_transaction.type === 'transfer') {
            $new_transaction.negative_total = $new_transaction.total *-1;
        }

        var $data = {
            description: $description,
            new_transaction: $new_transaction,
            filter: $filter
        };

        return $http.post($url, $data);
    };

    $object.updateMassTags = function ($tag_array, $url, $tag_location) {
        var $transaction_id;

        var $tag_id_array = $tag_array.map(function (el) {
            return el.tag_id;
        });

        $tag_id_array = JSON.stringify($tag_id_array);

        $(".checked").each(function () {
            $transaction_id = $(this).closest("tbody").attr('id');
            var $url = 'update/massTags';
            var $description = 'mass edit tags';
            var $data = {
                description: $description,
                transaction_id: $transaction_id,
                tag_id_array: $tag_id_array
            };

            return $http.post($url, $data);
        });
    };

    $object.massEditDescription = function () {
        var $transaction_id;
        var $description = $("#mass-edit-description-input").val();
        var $info = {
            transaction_id: $transaction_id,
            description: $description
        };

        $(".checked").each(function () {
            $transaction_id = $(this).closest("tbody").attr('id');

            var $url = 'update/massDescription';
            var $description = 'mass edit description';
            var $data = {
                description: $description,
                info: $info
            };

            return $http.post($url, $data);
        });
    };

    $object.updateTransaction = function ($transaction, $filter) {
        var $url = $transaction.path;

        //Make sure total is negative for an expense transaction
        if ($transaction.type === 'expense' && $transaction.total > 0) {
            $transaction.total = $transaction.total * -1;
        }

        var $data = {
            transaction: $transaction,
            filter: $filter
        };

        return $http.put($url, $data);
    };

    $object.updateReconciliation = function ($transaction_id, $reconciled, $filter) {
        var $url = 'update/reconciliation';

        if ($reconciled === true) {
            $reconciled = 'true';
        }
        else {
            $reconciled = 'false';
        }

        var $data = {
            id: $transaction_id,
            reconciled: $reconciled,
            filter: $filter
        };

        return $http.post($url, $data);
    };

    $object.deleteTransaction = function ($transaction, $filter) {
        var $url = 'delete/transaction';
        var $data = {
            transaction: $transaction,
            filter: $filter
        };

        return $http.post($url, $data);
    };

    $object.massDelete = function () {
        $(".checked").each(function () {
            deleteTransaction($(this));
        });
    };

	return $object;
});

app.factory('PreferencesFactory', function ($http) {
    return {
        savePreferences: function ($preferences) {
            var $url = 'update/settings';
            var $data = $preferences;

            return $http.post($url, $data);
        },
        insertOrUpdateDateFormat: function ($new_format) {
            var $url = 'insert/insertOrUpdateDateFormat';
            var $data = {
                new_format: $new_format
            };

            return $http.post($url, $data);
        }
    };
});
app.factory('TagsFactory', function ($http) {
    return {
        getTags: function () {
            var $url = 'select/tags';
            var $description = 'tags';
            var $data = {
                description: $description
            };

            return $http.post($url, $data);
        },
        duplicateTagCheck: function () {
            var $url = 'select/duplicate-tag-check';
            var $description = 'duplicate tag check';
            var $new_tag_name = $("#new-tag-input").val();
            var $data = {
                description: $description,
                new_tag_name: $new_tag_name
            };

            return $http.post($url, $data);
        },
        countTransactionsWithTag: function ($tag_id) {
            var $url = 'select/countTransactionsWithTag';
            var $description = 'count transactions with tag';
            var $data = {
                description: $description,
                tag_id: $tag_id
            };

            return $http.post($url, $data);
        },

        /**
         * Adds a new tag to tags table, not to a transaction
         * @returns {*}
         */
        insertTag: function () {
            var $url = 'insert/tag';
            var $description = 'tag';
            var $new_tag_name = $("#new-tag-input").val();
            var $data = {
                description: $description,
                new_tag_name: $new_tag_name
            };
            $("#tag-already-created").hide();

            return $http.post($url, $data);
        },

        updateTagName: function ($tag_id, $tag_name) {
            var $url = 'update/tagName';
            var $description = 'tag name';
            var $data = {
                description: $description,
                tag_id: $tag_id,
                tag_name: $tag_name
            };

            return $http.post($url, $data);

        },

        deleteTag: function ($tag_id) {
            var $url = 'delete/tag';
            var $description = 'tag';
            var $data = {
                description: $description,
                tag_id: $tag_id
            };

            return $http.post($url, $data);
        }
    };
});

app.factory('AccountsFactory', function ($http) {
    return {
        getAccounts: function () {
            var $url = 'select/accounts';
            var $description = 'accounts';
            var $data = {
                description: $description
            };

            return $http.post($url, $data);
        },
        insertAccount: function () {
            var $url = 'insert/account';
            var $description = 'account';
            var $name = $(".new_account_input").val();
            var $data = {
                description: $description,
                name: $name
            };

            return $http.post($url, $data);
        },
        updateAccountName: function ($account_id, $account_name) {
            var $url = 'update/accountName';
            var $description = 'account name';
            var $data = {
                description: $description,
                account_id: $account_id,
                account_name: $account_name
            };

            return $http.post($url, $data);

        },
        deleteAccount: function ($account_id) {
            var $url = 'delete/account';
            var $description = 'account';
            var $data = {
                description: $description,
                account_id: $account_id
            };

            return $http.post($url, $data);
        },

    };
});
app.factory('FilterFactory', function ($http) {
    var $object = {};

    $object.resetFilter = function () {
        $object.filter = {
            budget: "all",
            total: "",
            types: {
                in: [],
                out: []
            },
            accounts: {
                in: [],
                out: []
            },
            single_date: "",
            from_date: "",
            to_date: "",
            description: {
                in: "",
                out: ""
            },
            merchant: {
                in: "",
                out: ""
            },
            tags: [],
            reconciled: "any",
            offset: 0,
            num_to_fetch: 20
        };
    };

    $object.resetFilter();

    $object.multiSearch = function ($filter) {
        $object.filter = $filter;

        if ($filter.single_date) {
            $filter.single_date_sql = Date.parse($filter.single_date).toString('yyyy-MM-dd');
        }
        if ($filter.from_date) {
            $filter.from_date_sql = Date.parse($filter.from_date).toString('yyyy-MM-dd');
        }
        if ($filter.to_date) {
            $filter.to_date_sql = Date.parse($filter.to_date).toString('yyyy-MM-dd');
        }

        var $url = 'select/filter';
        var $data = {
            description: 'filter',
            filter: $filter
        };

        return $http.post($url, $data);
    };

    /**
     * For displaying the filtered transactions
     * and the filter totals
     * and the non-filter totals on the page
     * todo: maybe this should be in some totals factory
     * @param $data
     */
    $object.updateDataForControllers = function ($data) {
        if ($data.filter_results) {
            //This includes filtered transactions as well as filter totals
            $object.filter_results = $data.filter_results;
        }
        if ($data.totals) {
            //The non filter totals
            $object.totals = $data.totals;
        }
    };

    return $object;
});
app.factory('FeedbackFactory', function ($http) {
    var $object = {};

    $object.provideFeedback = function ($message) {
        //My watch in my controller would only work once unless I made an object here.
        //(Just $object.message would not work.)
        $object.data = {
            message: $message,
            update: true
        };
        return $object.data;
    };

    return $object;
});

;(function(){
    'use strict';
    angular
        .module('budgetApp')
        .directive('dropdownsDirective', dropdown);

    /* @inject */
    function dropdown($parse, $http) {
        return {
            restrict: 'EA',
            //scope: {
            //    //"id": "@id",
            //    //"selectedObject": "=selectedobject",
            //    'url': '@url',
            //    'showPopup': '=show'
            //},
            //templateUrl: 'templates/DropdownsTemplate.php',
            scope: true,
            link: function($scope, elem, attrs) {
                $scope.animateIn = attrs.animateIn || 'flipInX';
                $scope.animateOut = attrs.animateOut || 'flipOutX';
                var $content = $(elem).find('.dropdown-content');

                $scope.toggleDropdown = function () {
                    if ($($content).hasClass($scope.animateIn)) {
                        $scope.hideDropdown();
                    }
                    else {
                        $scope.showDropdown();
                    }
                };

                //Todo: Why is this click firing twice?
                $("body").on('click', function (event) {
                    if (!elem[0].contains(event.target)) {
                        $scope.hideDropdown();
                    }
                });

                $scope.showDropdown = function () {
                    $($content)
                        .css('display', 'flex')
                        .removeClass($scope.animateOut)
                        .addClass($scope.animateIn);
                };

                $scope.hideDropdown = function () {
                    $($content)
                        .removeClass($scope.animateIn)
                        .addClass($scope.animateOut);
                        //.css('display', 'none');
                };
            }
        };
    }
}).call(this);


;(function(){
    'use strict';
    angular
        .module('budgetApp')
        .directive('checkbox', checkbox);

    /* @inject */
    function checkbox() {
        return {
            restrict: 'EA',
            scope: {
                "model": "=model",
                "id": "@id"
            },
            templateUrl: 'checkboxes',
            link: function($scope, elem, attrs) {
                $scope.animateIn = attrs.animateIn || 'zoomIn';
                $scope.animateOut = attrs.animateOut || 'zoomOut';
                $scope.icon = $(elem).find('.label-icon');

                $scope.toggleIcon = function () {
                    if (!$scope.model) {
                        //Input was checked and now it won't be
                        $scope.hideIcon();
                    }
                    else {
                        //Input was not checked and now it will be
                        $scope.showIcon();
                    }
                };

                $scope.hideIcon = function () {
                    $($scope.icon).removeClass($scope.animateIn)
                        .addClass($scope.animateOut);
                };

                $scope.showIcon = function () {
                    $($scope.icon).css('display', 'flex')
                        .removeClass($scope.animateOut)
                        .addClass($scope.animateIn);
                };

                //Make the checkbox checked on page load if it should be
                if ($scope.model === true) {
                    $scope.showIcon();
                }

                $scope.$watch('model', function (newValue, oldValue) {
                    $scope.toggleIcon();
                });
            }
        };
    }
}).call(this);


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
            templateUrl: 'totals-directive',
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
                    $scope.totals.changes.RB.push($scope.format(newValue, oldValue));
                });

                //RBWEFLB
                $scope.$watch('totals.budget.RBWEFLB', function (newValue, oldValue) {
                    if (!oldValue || newValue === oldValue) {
                        return;
                    }
                    $scope.totals.changes.RBWEFLB.push($scope.format(newValue, oldValue));
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


////File not being used
//;(function(){
//    'use strict';
//    angular
//        .module('budgetApp')
//        .directive('filterDirective', filter);
//
//    /* @inject */
//    function filter() {
//        return {
//            restrict: 'EA',
//            scope: {
//                "showFilter": "=show",
//                "accounts": "=accounts",
//                "multiSearch": "&search"
//            },
//            templateUrl: 'filter',
//            //scope: true,
//            link: function($scope, elem, attrs) {
//                $scope.resetFilter = function () {
//                    $scope.filter = {
//                        budget: "all",
//                        total: "",
//                        types: [],
//                        accounts: [],
//                        single_date: "",
//                        from_date: "",
//                        to_date: "",
//                        description: "",
//                        merchant: "",
//                        tags: [],
//                        reconciled: "any",
//                        offset: 0,
//                        num_to_fetch: 20
//                    };
//                };
//
//                $scope.resetFilter();
//
//
//
//                $scope.$watchCollection('filter.accounts', function (newValue, oldValue) {
//                    if (newValue === oldValue) {
//                        return;
//                    }
//                    $scope.multiSearch(true);
//                });
//            }
//        };
//    }
//}).call(this);
//

;(function(){
    'use strict';
    angular
        .module('budgetApp')
        .directive('tagAutocompleteDirective', tagAutocomplete);

    /* @inject */
    function tagAutocomplete(FeedbackFactory, $sce) {
        return {
            restrict: 'EA',
            scope: {
                "chosenTags": "=chosentags",
                "dropdown": "=dropdown",
                "tags": "=tags",
                "fnOnEnter": "&fnonenter",
                "multipleTags": "=multipletags",
                "model": "=model",
                //"typing": "=modelname",
                "id": "@id",
                "focusOnEnter": "@focusonenter"
            },
            templateUrl: 'tag-autocomplete',
            link: function($scope, elem, attrs) {
                $scope.results = {};
                $scope.messages = {};

                /**
                 * Check for duplicate tags when adding a new tag to an array
                 * @param $tag_id
                 * @param $tag_array
                 * @returns {boolean}
                 */
                $scope.duplicateTagCheck = function ($tag_id, $tag_array) {
                    for (var i = 0; i < $tag_array.length; i++) {
                        if ($tag_array[i].id === $tag_id) {
                            return false; //it is a duplicate
                        }
                    }
                    return true; //it is not a duplicate
                };


                $scope.chooseTag = function ($index) {
                    if ($index !== undefined) {
                        //Item was chosen by clicking, not by pressing enter
                        $scope.currentIndex = $index;
                    }

                    if ($scope.multipleTags) {
                        $scope.addTag();
                    }
                    else {
                        $scope.fillField();
                    }
                };

                /**
                 * For if only one tag can be chosen
                 */
                $scope.fillField = function () {
                    $scope.typing = $scope.results[$scope.currentIndex].name;
                    $scope.model = $scope.results[$scope.currentIndex];
                    if ($scope.focusOnEnter) {
                        // Todo: This line doesn't work if tag is chosen with mouse click
                        $("#" + $scope.focusOnEnter).focus();
                    }
                    $scope.hideAndClear();
                };

                /**
                 * For if multiple tags can be chosen
                 */
                $scope.addTag = function () {
                    var $tag_id = $scope.results[$scope.currentIndex].id;

                    if (!$scope.duplicateTagCheck($tag_id, $scope.chosenTags)) {
                        FeedbackFactory.provideFeedback('You have already entered that tag');
                        $scope.hideAndClear();
                        return;
                    }

                    $scope.chosenTags.push($scope.results[$scope.currentIndex]);
                    $scope.hideAndClear();
                };

                /**
                 * Hide the dropdown and clear the input field
                 */
                $scope.hideAndClear = function () {
                    $scope.hideDropdown();

                    if ($scope.multipleTags) {
                        $scope.typing = '';
                    }

                    $scope.currentIndex = null;
                    $('.highlight').removeClass('highlight');
                };

                $scope.hideDropdown = function () {
                    $scope.dropdown = false;
                };

                $scope.highlightLetters = function ($response, $typing) {
                    $typing = $typing.toLowerCase();

                    for (var i = 0; i < $response.length; i++) {
                        var $name = $response[i].name;
                        var $index = $name.toLowerCase().indexOf($typing);
                        var $substr = $name.substr($index, $typing.length);
                        var $html = $sce.trustAsHtml($name.replace($substr, '<span class="highlight">' + $substr + '</span>'));
                        $response[i].html = $html;
                    }
                    return $response;
                };

                $scope.hoverItem = function(index) {
                    $scope.currentIndex = index;
                };

                /**
                 * Act on keypress for input field
                 * @param $keycode
                 * @returns {boolean}
                 */
                $scope.filterTags = function ($keycode) {
                    if ($keycode === 13) {
                        //enter is pressed
                        //$scope.chooseItem();

                        if (!$scope.results[$scope.currentIndex]) {
                            //We are not adding a tag. We are inserting the transaction.
                            $scope.fnOnEnter();
                            return;
                        }
                        //We are choosing a tag
                        $scope.chooseTag();

                        //resetting the dropdown to show all the tags again after a tag has been added
                        $scope.results = $scope.tags;
                    }
                    else if ($keycode === 38) {
                        //up arrow is pressed
                        if ($scope.currentIndex > 0) {
                            $scope.currentIndex--;
                        }
                    }
                    else if ($keycode === 40) {
                        //down arrow is pressed
                        if ($scope.currentIndex + 1 < $scope.results.length) {
                            $scope.currentIndex++;
                        }
                    }
                    else {
                        //Not enter, up or down arrow
                        $scope.currentIndex = 0;
                        $scope.showDropdown();
                    }
                };

                /**
                 * Todo: when the new budget tag input is focused after entering a budget,
                 * todo: I don't want the dropdown to show. I had a lot of trouble and need help though.
                 */
                $scope.showDropdown = function () {
                    $scope.dropdown = true;
                    if ($scope.typing) {
                        $scope.results = $scope.highlightLetters($scope.searchLocal(), $scope.typing);
                    }
                };

                $scope.searchLocal = function () {
                    var $filtered_tags = _.filter($scope.tags, function ($tag) {
                        return $tag.name.toLowerCase().indexOf($scope.typing.toLowerCase()) !== -1;
                    });

                    return $filtered_tags;
                };

                $scope.removeTag = function ($tag) {
                    $scope.chosenTags = _.without($scope.chosenTags, $tag);
                };
            }
        };
    }
}).call(this);


;(function(){
    'use strict';
    angular
        .module('budgetApp')
        .directive('filterDropdownsDirective', filterDropdown);

    /* @inject */
    function filterDropdown($parse, $http) {
        return {
            restrict: 'A',
            //scope: {
            //    //"model": "=model",
            //    //"id": "@id"
            //    "types": "=types",
            //    "path": "@path"
            //},
            //templateUrl: 'filter-dropdowns',
            scope: true,
            link: function($scope, elem, attrs) {
                $scope.content = $(elem).find('.content');
                var $h4 = $(elem).find('h4');

                $($h4).on('click', function () {
                    $scope.toggleContent();
                });

                $scope.toggleContent = function () {
                    if ($scope.contentVisible) {
                        $scope.hideContent();
                    }
                    else {
                        $scope.showContent();
                    }
                };

                $scope.showContent = function () {
                    $scope.content.slideDown();
                    $scope.contentVisible = true;
                };

                $scope.hideContent = function () {
                    $scope.content.slideUp();
                    $scope.contentVisible = false;
                };
            }
        };
    }
}).call(this);


;(function(){
    'use strict';
    angular
        .module('budgetApp')
        .directive('transactionAutocompleteDirective', transactionAutocomplete);

    /* @inject */
    function transactionAutocomplete(FeedbackFactory, AutocompleteFactory, $sce, $http) {
        return {
            restrict: 'EA',
            scope: {
                "dropdown": "=dropdown",
                "placeholder": "@placeholder",
                "typing": "=typing",
                "new_transaction": "=newtransaction",
                "fnOnEnter": "&fnonenter"
            },
            templateUrl: 'transaction-autocomplete',
            link: function($scope, elem, attrs) {
                $scope.results = {};

                /**
                 * Hide the dropdown and clear the input field
                 */
                $scope.hideAndClear = function () {
                    $scope.hideDropdown();
                    $scope.currentIndex = null;
                    $('.highlight').removeClass('highlight');
                };

                $scope.hideDropdown = function () {
                    $scope.dropdown = false;
                };

                $scope.highlightLetters = function ($response, $typing) {
                    $typing = $typing.toLowerCase();

                    for (var i = 0; i < $response.length; i++) {
                        var $name = $response[i].name;
                        var $index = $name.toLowerCase().indexOf($typing);
                        var $substr = $name.substr($index, $typing.length);
                        var $html = $sce.trustAsHtml($name.replace($substr, '<span class="highlight">' + $substr + '</span>'));
                        $response[i].html = $html;
                    }
                    return $response;
                };

                $scope.hoverItem = function(index) {
                    $scope.currentIndex = index;
                };

                /**
                 * Act on keypress for input field
                 * @param $keycode
                 * @returns {boolean}
                 */
                $scope.filter = function ($keycode) {
                    if ($keycode === 13) {
                        //enter is pressed
                        if (!$scope.results[$scope.currentIndex]) {
                            //We are not adding a tag. We are inserting the transaction.
                            $scope.fnOnEnter();
                            return;
                        }
                        //We are adding a tag
                        $scope.chooseItem();

                        //resetting the dropdown to show all the tags again after a tag has been added
                        //$scope.results = $scope.tags;
                    }
                    else if ($keycode === 38) {
                        //up arrow is pressed
                        if ($scope.currentIndex > 0) {
                            $scope.currentIndex--;
                        }
                    }
                    else if ($keycode === 40) {
                        //down arrow is pressed
                        if ($scope.currentIndex + 1 < $scope.results.length) {
                            $scope.currentIndex++;
                        }
                    }
                    else {
                        //Not enter, up or down arrow
                        $scope.currentIndex = 0;
                        $scope.showDropdown();
                    }
                };

                $scope.showDropdown = function () {
                    $scope.dropdown = true;
                    $scope.results = $scope.highlightLetters($scope.searchDatabase(), $scope.typing);
                };

                $scope.searchLocal = function () {
                    var $results = _.filter($scope.tags, function ($tag) {
                        return $tag.name.toLowerCase().indexOf($scope.typing.toLowerCase()) !== -1;
                    });

                    return $results;
                };

                /**
                 * Query the database
                 */
                $scope.searchDatabase = function () {
                    var $data = {
                        typing: $scope.typing,
                        column: $scope.placeholder
                    };

                    return $http.post('select/autocompleteTransaction', $data).
                        success(function(response, status, headers, config) {
                            //$scope.dealWithResults(response);
                            $scope.results = response;
                            $scope.results = AutocompleteFactory.transferTransactions($scope.results);
                            $scope.results = AutocompleteFactory.removeDuplicates($scope.results);
                        }).
                        error(function(data, status, headers, config) {
                            console.log("There was an error");
                        });
                };

                $scope.chooseItem = function ($index) {
                    if ($index !== undefined) {
                        //Item was chosen by clicking, not by pressing enter
                        $scope.currentIndex = $index;
                    }

                    $scope.selectedItem = $scope.results[$scope.currentIndex];

                    $scope.fillFields();

                    $scope.hideAndClear();
                };

                $scope.fillFields = function () {
                    if ($scope.placeholder === 'description') {
                        $scope.typing = $scope.selectedItem.description;
                        $scope.new_transaction.merchant = $scope.selectedItem.merchant;
                    }
                    else if ($scope.placeholder === 'merchant') {
                        $scope.typing = $scope.selectedItem.merchant;
                        $scope.new_transaction.description = $scope.selectedItem.description;
                    }

                    $scope.new_transaction.total = $scope.selectedItem.total;
                    $scope.new_transaction.type = $scope.selectedItem.type;
                    $scope.new_transaction.account = $scope.selectedItem.account.id;

                    if ($scope.selectedItem.from_account && $scope.selectedItem.to_account) {
                        $scope.new_transaction.from_account = $scope.selectedItem.from_account.id;
                        $scope.new_transaction.to_account = $scope.selectedItem.to_account.id;
                    }

                    $scope.new_transaction.tags = $scope.selectedItem.tags;
                };

            }
        };
    }
}).call(this);


//# sourceMappingURL=all.js.map