var app = angular.module('budgetApp', ['checklist-model', 'ngAnimate'], function ($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});

(function () {

	app.controller('mainCtrl', function ($scope, $http, autocomplete, totals, budgets, savings, settings, transactions, preferences) {

		/**
		 * scope properties
		 */

		/*=========new_transaction=========*/
		$scope.new_transaction = {
			total: '5',
			type: 'income',
			description: '',
			merchant: '',
			date: {
				entered: 'today'
			},
			reconciled: false,
			multiple_budgets: false
		};

		$scope.preferences = {};

		$scope.totals = {};

        $scope.me = me;
				
		$scope.new_transaction.tags = [
			// {
			// 	id: '16',
			// 	name: 'test',
			// 	fixed_budget: '10.00',
			// 	flex_budget: null
			// },
			// {
			// 	id: '17',
			// 	name: 'testtwo',
			// 	fixed_budget: null,
			// 	flex_budget: '5'
			// }
		];

		/*=========transactions=========*/
		//$scope.transactions = [];
        $scope.transactions = filter_response.transactions;
        $scope.totals.filter = filter_response.filter_totals;
		$scope.transactions_limited = []; //limited to 30 results

		/*=========edit=========*/
		$scope.edit_transaction = {
			tags: []
		};
		$scope.edit_tag = {};
		$scope.edit_account = {};
		$scope.edit_CSD = {};

		/*=========filter=========*/

		$scope.resetFilter = function () {
			$scope.filter = {
				budget: "all",
				total: "",
				types: [],
				accounts: [],
				single_date: "",
				from_date: "",
				to_date: "",
				description: "",
				merchant: "",
				tags: [],
				reconciled: "any",
				offset: 0,
				num_to_fetch: 20
			};
		};

		$scope.resetFilter();

		/*=========types=========*/

		$scope.types = ["income", "expense", "transfer"];

		/*=========other=========*/
		$scope.allocation_popup_transaction = {};
		$scope.tags = {};
		$scope.accounts = {};
		$scope.selected = {}; //for getting the selected tag in autocomplete
		$scope.typing = {
			new_transaction: {
				tag: ''
			}
		};
		$scope.autocomplete = {
			transactions: {},
			tags: {}
		};
		$scope.colors = {};
		$scope.messages = {};
		$scope.tag_input = ""; //for the inputs where the tag is autocompleted

		/*=========selected=========*/

		$scope.selected = {};

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
			filter: false,
			autocomplete: {
				transactions: false
			},
			allocation_popup: false,
			new_transaction_allocation_popup: false,
			savings_total: {
				input: false,
				edit_btn: true
			}
		};

		/**
		 * select
		 */
		
		$scope.getTags = function () {
			settings.getTags().then(function (response) {
				$scope.tags = response.data;
				$scope.autocomplete.tags = response.data;
			});
		};
		
		$scope.getAccounts = function () {
			settings.getAccounts().then(function (response) {
				$scope.accounts = response.data;
				if ($scope.accounts[0]) {
					//this if check is to get rid of the error for a new user who does not yet have any accounts.
					$scope.new_transaction.account = $scope.accounts[0].id;
					$scope.new_transaction.from_account = $scope.accounts[0].id;
					$scope.new_transaction.to_account = $scope.accounts[0].id;
				}	
			});
		};
		
		$scope.multiSearch = function ($reset, $new_transaction) {
			transactions.multiSearch($scope.filter, $reset).then(function (response) {
				$scope.transactions = response.data.transactions;
				$scope.totals.filter = response.data.filter_totals;


				if ($new_transaction && $scope.new_transaction.multiple_budgets) {
					//multiSearch has been called after entering a new transaction.
					//The new transaction has multiple budgets.
					//Find the transaction that was just entered in $scope.transactions.
					//This is so that the transaction is updated live when actions are done in the allocation popup. Otherwise it will need a page refresh. 
					$transaction = _.find($scope.transactions, function ($scope_transaction) {
						return $scope_transaction.id === $scope.allocation_popup_transaction.id;
					});

					if ($transaction) {
						$scope.showAllocationPopup($transaction);
					}
					else {
						//the transaction isn't showing with the current filter settings
						$scope.showAllocationPopup($scope.allocation_popup_transaction);
					}
				}
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
			if ($scope.filter.offset + ($scope.filter.num_to_fetch * 1) > $scope.totals.filter.num_transactions) {
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

		$(".clear-search-button").on('click', function () {
			if ($(this).attr('id') == "clear-tags-btn") {
				$search_tag_array.length = 0;
				$("#search-tag-location").html($search_tag_array);
			}
			$(this).closest(".input-group").children("input").val("");
			$scope.multiSearch(true);
		});

		/**
		 * insert
		 */

		$scope.addTagToTransaction = function ($tags) {
			$scope.messages.tag_exists = false;
			var $tag_id = $scope.selected.id;

			if ($scope.duplicateTagCheck($tag_id, $tags)) {
				$tags.push($scope.selected);

				autocomplete.removeSelected($scope.autocomplete.tags);
			}
			else {
				$scope.messages.tag_exists = true;
			}

			//clearing the tag input
			$scope.typing = {};
		};

		$scope.errorCheck = function () {
			$scope.messages = {};

			var $date_entry = $("#date").val();
			$scope.new_transaction.date.sql = Date.parse($date_entry).toString('yyyy-MM-dd');

			if ($scope.new_transaction.date.sql === null) {
				$scope.messages.invalid_date = true;
				return false;
			}
			else if ($scope.new_transaction.total === "") {
				$scope.message.total_required = true;
				return false;
			}
			else if (!$.isNumeric($scope.new_transaction.total)) {
				$scope.messages.total_not_number = true;
				return false;
			}
			else if ($scope.new_transaction.type === 'transfer' && $scope.new_transaction.from_account === "from") {
				$scope.messages.from_account_required = true;
				return false;
			}
			else if ($scope.new_transaction.type === 'transfer' && $scope.new_transaction.to_account === "to") {
				$scope.messages.to_account_required = true;
				return false;
			}
			return true;
		};

		$scope.clearNewTransactionValues = function () {
			$transaction_tag_array.length = 0;
			$("#new-transaction-tr").find(".merchant, .total, .description").val("");
			$("#new-transaction-tr .checkbox_container").removeClass('reconciled');
			$("#select_transaction_type").focus();
		};

		$scope.insertTransaction = function ($keycode) {
			if ($keycode !== 13 || !$scope.errorCheck()) {
				return;
			}

			transactions.insertTransaction($scope.new_transaction).then(function (response) {
				//see if the transaction that was just entered has multiple budgets
				//the allocation popup is shown from $scope.multiSearch().
				var $transaction = response.data.transaction;
				var $multiple_budgets = response.data.multiple_budgets;

				if ($multiple_budgets) {
					$scope.new_transaction.multiple_budgets = true;
					$scope.allocation_popup_transaction = $transaction;
				}
				else {
					$scope.new_transaction.multiple_budgets = false;
				}

				$scope.getTotals();
				$scope.multiSearch(false, true);
				//clearing the new transaction tags
				$scope.new_transaction.tags = [];
			});
			$scope.new_transaction.dropdown = false;
		};

		/**
		 * update
		 */
		
		$scope.updateColors = function () {
			settings.updateColors($scope.colors).then(function (response) {
				$scope.getColors();
				$scope.show.color_picker = false;
			});
		};
		
		$scope.updateReconciliation = function ($transaction_id, $reconciliation) {
			transactions.updateReconciliation($transaction_id, $reconciliation).then(function (response) {
				$scope.multiSearch();
				$scope.getTotals();
			});
		};

		$scope.updateTagSelectHTML = function () {
			transactions.updateTagSelectHTML().then(function (response) {
				$("#fixed-budget-tag-select").html('<option>Fixed Budget</option>' + response);
				$("#flex-budget-tag-select").html('<option>Flex Budget</option>' + response);
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

		$scope.updateTransactionSetup = function ($transaction) {
			$scope.edit_transaction = $transaction;
			//save the original total so I can calculate the difference if the total changes, so I can remove the correct amount from savings if required.
			$scope.edit_transaction.original_total = $scope.edit_transaction.total;
			$scope.show.edit_transaction = true;
		};

		$scope.updateTransaction = function () {
			var $date_entry = $("#edit-transaction-date").val();
			$scope.edit_transaction.date.user = $date_entry;
			$scope.edit_transaction.date.sql = Date.parse($date_entry).toString('yyyy-MM-dd');
			transactions.updateTransaction($scope.edit_transaction).then(function (response) {
				$scope.multiSearch();
				$scope.show.edit_transaction = false;

				//if it is an income transaction, and if the total has decreased, remove a percentage from savings
				if ($scope.edit_transaction.type === 'income') {
                    var $new_total = $scope.edit_transaction.total;
                    $new_total = parseInt($new_total.replace(',', ''), 10);
                    var $original_total = $scope.edit_transaction.original_total;
                    $original_total = parseInt($original_total.replace(',', ''), 10);

                    if ($new_total < $original_total) {
                        //income transaction total has decreased. subtract percentage from savings
                        var $diff = $original_total - $new_total;
                        //this percent is temporary
                        var $percent = 10;
                        var $amount_to_subtract = $diff / 100 * $percent;
                        $scope.reverseAutomaticInsertIntoSavings($amount_to_subtract);
                    }
                }

				$scope.getTotals();
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
			transactions.updateMassTags().then(function (response) {
				multiSearch();
				$tag_array.length = 0;
				$tag_location.html($tag_array);
			});
		};

		$scope.massEditDescription = function () {
			transactions.updateMassDescription().then(function (response) {
				multiSearch();
			});
		};

		// =================================allocation=================================

		$scope.showAllocationPopup = function ($transaction) {
			$scope.show.allocation_popup = true;
			$scope.allocation_popup_transaction = $transaction;
			
			budgets.getAllocationTotals($transaction.id).then(function (response) {
				$scope.allocation_popup_transaction.allocation_totals = response.data;
			});
		};

		$scope.updateAllocation = function ($keycode, $type, $value, $tag_id) {
			if ($keycode === 13) {
				budgets.updateAllocation($type, $value, $scope.allocation_popup_transaction.id, $tag_id).then(function (response) {
					//find the tag in $scope.allocation_popup_transaction.tags
					var $the_tag = _.find($scope.allocation_popup_transaction.tags, function ($tag) {
						return $tag.id === $tag_id;
					});
					//get the index of the tag in $scope.allocation_popup_transaction.tags
					var $index = _.indexOf($scope.allocation_popup_transaction.tags, $the_tag);
					//make the tag equal the ajax response
					$scope.allocation_popup_transaction.tags[$index] = response.data.allocation_info;
					$scope.allocation_popup_transaction.allocation_totals = response.data.allocation_totals;
				});
			}
		};

		$scope.updateAllocationStatus = function () {
			budgets.updateAllocationStatus($scope.allocation_popup_transaction.id, $scope.allocation_popup_transaction.allocated).then(function (response) {
				console.log("something");
			});
		};

		/**
		 * delete
		 */

		$("#search-div").on('click', '#search-tag-location li', function () {
			removeTag(this, $search_tag_array, $("#search-tag-location"), multiSearch);
		});

		$("#new-transaction-tbody").on('click', '#new-transaction-tag-location li', function () {
			removeTag(this, $transaction_tag_array, $("#new-transaction-tag-location"));
		});

		$scope.removeTag = function ($tag, $array, $scope_property) {
			$scope[$scope_property]['tags'] = _.without($array, $tag);
		};

		$scope.deleteTransaction = function ($transaction) {
			if (confirm("Are you sure?")) {
				transactions.deleteTransaction($transaction.id).then(function (response) {
					$scope.multiSearch();

					//reverse the automatic insertion into savings if it is an income expense
					if ($transaction.type === 'income') {
						//This value will change. Just for developing purposes.
						var $percent = 10;
						var $amount_to_subtract = $transaction.total / 100 * $percent;
						$scope.reverseAutomaticInsertIntoSavings($amount_to_subtract);
					}

					$scope.getTotals();
				});
			}
		};

		$("#mass-delete-button").on('click', function () {
			if (confirm("You are about to delete " + $(".checked").length + " transactions. Are you sure you want to do this?")) {
				massDelete();
			}
		});

		/**
		 * filter
		 */

		 /**
		  * Almost duplicate of filterTags in budgets controller
		  * @param  {[type]} $keycode           [description]
		  * @param  {[type]} $typing            [description]
		  * @param  {[type]} $location_for_tags [description]
		  * @param  {[type]} $scope_property    [description]
		  * @return {[type]}                    [description]
		  */
		$scope.filterTags = function ($keycode, $typing, $location_for_tags, $scope_property) {
			if ($keycode !== 38 && $keycode !== 40 && $keycode !== 13) {
				//not up arrow, down arrow or enter, so filter tags
				autocomplete.removeSelected($scope.tags);
				$scope[$scope_property]['dropdown'] = true;
				$scope.autocomplete.tags = autocomplete.filterTags($scope.tags, $typing);
				if ($typing !== "" && $scope.autocomplete.tags.length > 0) {
					$scope.selected = autocomplete.selectFirstItem($scope.autocomplete.tags);
				}
			}
			else if ($keycode === 38) {
				//up arrow
				$scope.selected = autocomplete.upArrow($scope.autocomplete.tags);
			}
			else if ($keycode === 40) {
				//down arrow
				$scope.selected = autocomplete.downArrow($scope.autocomplete.tags);
			}
			else if ($keycode === 13) {
				var $selected = $("#new-transaction .selected");
				if ($selected.length === 0 && $location_for_tags === $scope.new_transaction.tags) {
					//We are not adding a tag. We are inserting the transaction.
					$scope.insertTransaction(13);
					return;
				}
				//We are adding a tag
				$scope.addTagToTransaction($location_for_tags);

				//resetting the dropdown to show all the tags again after a tag has been added
				$scope.autocomplete.tags = $scope.tags;
			}
		};

		

		$scope.filterTransactions = function ($keycode, $typing, $field) {
			//for the transaction autocomplete
			$scope.show.autocomplete.transactions = true;
			if ($keycode !== 38 && $keycode !== 40 && $keycode !== 13) {
				//not up arrow, down arrow or enter, so filter transactions
				autocomplete.removeSelected($scope.transactions);
				//fetch the transactions that match $typing to display in the autocomplete dropdown
				autocomplete.filterTransactions($typing, $field).then(function (response) {
					$scope.autocomplete.transactions = response.data;
					$scope.autocomplete.transactions = autocomplete.transferTransactions($scope.autocomplete.transactions);
					$scope.autocomplete.transactions = autocomplete.removeDuplicates($scope.autocomplete.transactions);
				});			
			}
			else if ($keycode === 38) {
				//up arrow
				$scope.selected = autocomplete.upArrow($scope.autocomplete.transactions);
			}
			else if ($keycode === 40) {
				//down arrow
				$scope.selected = autocomplete.downArrow($scope.autocomplete.transactions);
			}
			else if ($keycode === 13) {
				var $selected = _.find($scope.autocomplete.transactions, function ($item) {
					return $item.selected === true;
				});
				if ($selected) {
					//fill in the fields
					$scope.autocompleteTransaction();
				}
				else {
					$scope.insertTransaction(13);
				}
			}
		};

		$scope.autocompleteTransaction = function ($selected) {
			//fills in the fields
			$selected = $selected || _.find($scope.autocomplete.transactions, function ($transaction) {
				return $transaction.selected === true;
			});
			$scope.new_transaction.description = $selected.description;
			$scope.new_transaction.merchant = $selected.merchant;
			$scope.new_transaction.total = $selected.total;
			$scope.new_transaction.type = $selected.type;
			$scope.new_transaction.account = $selected.account.id;

			if ($selected.from_account && $selected.to_account) {
				$scope.new_transaction.from_account = $selected.from_account.id;
				$scope.new_transaction.to_account = $selected.to_account.id;
			}
			
			$scope.new_transaction.tags = $selected.tags;

			$scope.show.autocomplete = false;

			autocomplete.removeSelected($scope.transactions);
			autocomplete.removeSelected($scope.autocomplete.transactions);
		};

		/**
		 * watches
		 */
		
		$scope.$watch('preferences.date_format', function (newValue, oldValue) {
			if (!newValue) {
				return;
			}
			preferences.insertOrUpdateDateFormat(newValue).then(function (response) {
				// $scope. = response.data;
			});
		});

		$scope.$watchCollection('colors', function (newValue) {
			$("#income-color-picker").val(newValue.income);
			$("#expense-color-picker").val(newValue.expense);
			$("#transfer-color-picker").val(newValue.transfer);
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

		$scope.$watchCollection('filter.accounts', function (newValue, oldValue) {
			if (newValue === oldValue) {
				return;
			}
			$scope.multiSearch(true);
		});

		$scope.$watchCollection('filter.types', function (newValue, oldValue) {
			if (newValue === oldValue) {
				return;
			}
			$scope.multiSearch(true);
		});

		$scope.$watch('filter.description', function (newValue, oldValue) {
			if (newValue === oldValue) {
				return;
			}
			$scope.resetOffset();
			$scope.multiSearch(true);
		});

		$scope.$watch('filter.merchant', function (newValue, oldValue) {
			if (newValue === oldValue) {
				return;
			}
			$scope.resetOffset();
			$scope.multiSearch(true);
		});

		$scope.$watchCollection('filter.tags', function (newValue, oldValue) {
			if (newValue === oldValue) {
				return;
			}
			$scope.multiSearch(true);
		});

		$scope.$watch('filter.single_date', function (newValue, oldValue) {
			if (newValue === oldValue) {
				return;
			}
			if (newValue === "") {
				$scope.filter.single_date_sql = "";
				$scope.multiSearch(true);
			}
		});

		$scope.$watch('filter.from_date', function (newValue, oldValue) {
			if (newValue === oldValue) {
				return;
			}
			if (newValue === "") {
				$scope.filter.from_date_sql = "";
				$scope.multiSearch(true);
			}
		});

		$scope.$watch('filter.to_date', function (newValue, oldValue) {
			if (newValue === oldValue) {
				return;
			}
			if (newValue === "") {
				$scope.filter.to_date_sql = "";
				$scope.multiSearch(true);
			}
		});

		$scope.$watch('filter.total', function (newValue, oldValue) {
			if (newValue === oldValue) {
				return;
			}
			$scope.multiSearch(true);
		});

		$scope.$watch('filter.reconciled', function (newValue, oldValue) {
			if (newValue === oldValue) {
				return;
			}
			$scope.multiSearch(true);
		});

		$scope.$watch('filter.budget', function (newValue, oldValue) {
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
		 * other
		 */
		
		$scope.getColors = function () {
			settings.getColors().then(function (response) {
				$scope.colors = response.data;
			});
		};

		/**
		 * Needed for filter
		 * @param  {[type]} $keycode [description]
		 * @param  {[type]} $func    [description]
		 * @param  {[type]} $params  [description]
		 * @return {[type]}          [description]
		 */
		$scope.checkKeycode = function ($keycode, $func, $params) {
			if ($keycode === 13) {
				$func($params);
			}
		};

		$scope.clearFilterField = function ($field) {
			if ($field === 'tags') {
				$scope.filter.tags = [];
			}
			else {
				$scope.filter[$field] = "";
			}
		};

		$scope.resetOffset = function () {
			$scope.filter.offset = 0;
		};

		$scope.duplicateTagCheck = function ($tag_id, $tag_array) {
			//checks for duplicate tags when adding a new tag to an array
			for (var i = 0; i < $tag_array.length; i++) {
				if ($tag_array[i].tag_id === $tag_id) {
					return false; //it is a duplicate
				}
			}
			return true; //it is not a duplicate
		};

		/**
		 * totals
		 */

		$scope.getTotals = function () {
			totals.basicTotals().then(function (response) {
				$scope.totals.basic = response.data;
			});
			totals.budget().then(function (response) {
				$scope.totals.budget = response.data;
			});
		};

		$scope.updateSavingsTotal = function ($keycode) {
			if ($keycode !== 13) {
				return;
			}
			savings.updateSavingsTotal().then(function (response) {
				$scope.totals.basic.savings_total = response.data;
				$scope.show.savings_total.input = false;
				$scope.show.savings_total.edit_btn = true;
				$scope.getTotals();
			});
		};

		$scope.addFixedToSavings = function ($keycode) {
			if ($keycode !== 13) {
				return;
			}
			savings.addFixedToSavings().then(function (response) {
				$scope.totals.basic.savings_total = response.data;
				$scope.getTotals();
			});
		};

		$scope.addPercentageToSavingsAutomatically = function ($amount_to_add) {
			savings.addPercentageToSavingsAutomatically($amount_to_add).then(function (response) {
				$scope.totals.basic.savings_total = response.data;
				$scope.getTotals();
			});
		};

		$scope.reverseAutomaticInsertIntoSavings = function ($amount_to_subtract) {
			savings.reverseAutomaticInsertIntoSavings($amount_to_subtract).then(function (response) {
				$scope.totals.basic.savings_total = response.data;
				$scope.getTotals();
			});
		};

		$scope.addPercentageToSavings = function ($keycode) {
			if ($keycode !== 13) {
				return;
			}
			savings.addPercentageToSavings().then(function (response) {
				$scope.totals.basic.savings_total = response.data;
				$scope.getTotals();
			});
		};

		$scope.updateChart = function () {
				$(".bar_chart_li:first-child").css('height', '0%');
				$(".bar_chart_li:nth-child(2)").css('height', '0%');
				$(".bar_chart_li:first-child").css('height', getTotal()[6] + '%');
				$(".bar_chart_li:nth-child(2)").css('height', getTotal()[5] + '%');
		};

		/**
		 * show
		 */
		
		$scope.showSavingsTotalInput = function () {
			$scope.show.savings_total.input = true;
			$scope.show.savings_total.edit_btn = false;
		};

		$scope.toggleFilter = function () {
			$scope.show.filter = !$scope.show.filter;
		};



		/**
		 * page load
		 */

		//$scope.multiSearch();
		$scope.getColors();
		$scope.getTotals();
		$scope.getAccounts();
		$scope.getTags();

	}); //end controller


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

})();