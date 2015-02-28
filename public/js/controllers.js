var app = angular.module('budgetApp', ['checklist-model']);

(function () {

	// ===========================display controller===========================

	app.controller('mainCtrl', function ($scope, $http, insert, select, update, deleteItem, filter, autocomplete, totals) {

		/*=================================================================================
		===================================================================================
		===================================================================================
		=================================$scope properties=================================
		===================================================================================
		===================================================================================
		=================================================================================*/
		
		$scope.tab = 'home';

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
		
		//$scope.new_transaction.account is set within $scope.getAccounts (as well as from and to for the transfers)
		// $scope.new_transaction.from_account = 'from';
		// $scope.new_transaction.to_account = 'to';
		$scope.new_transaction.tags = [
			{
				id: '1',
				name: 'chiropractic',
				fixed_budget: '10.00',
				flex_budget: null
			},
			{
				id: '2',
				name: 'church',
				fixed_budget: null,
				flex_budget: '5'
			}
		];

		/*=========budget=========*/

		$scope.new_fixed_budget = {
			tag: {
				name: "",
				id: ""
			},
			budget: "",
			dropdown: false
		};

		$scope.new_flex_budget = {
			tag: {
				name: "",
				id: ""
			},
			budget: "",
			dropdown: false
		};

		/*=========transactions=========*/
		$scope.transactions = [];
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
				num_to_fetch: 10
			};
		};

		$scope.resetFilter();

		/*=========types=========*/

		$scope.types = ["income", "expense", "transfer"];

		/*=========other=========*/
		$scope.allocation_popup_transaction = {};
		$scope.totals = {};
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

		// $scope.display_from = 1;
		// $scope.display_to = 30;
		// $scope.counter

		/*=========selected=========*/

		$scope.selected = {};

		/*=========show=========*/
		$scope.show = {
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
				transactions: false
			},
			allocation_popup: false,
			new_transaction_allocation_popup: false
			// budget: {
			// 	dropdown: false
			// }
		};
		
		/*=================================================================================
		===================================================================================
		===================================================================================
		=================================select=================================
		===================================================================================
		===================================================================================
		=================================================================================*/
		
		$scope.multiSearch = function ($reset, $new_transaction) {
			select.multiSearch($scope.filter, $reset).then(function (response) {
				$scope.transactions = response.data;
				$scope.multiSearchTags();
				$scope.multiSearchBudget();
				$scope.getFilterTotals();
				$scope.searchResults();

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

		$scope.multiSearchTags = function () {
			$scope.transactions = filter.tags($scope.transactions, $scope.filter.tags);
		};

		$scope.multiSearchBudget = function () {
			$scope.transactions = filter.budget($scope.transactions, $scope.filter.budget);
		};

		$scope.prevResults = function () {
			//make it so the offset cannot be less than 0.
			if ($scope.filter.offset - $scope.filter.num_to_fetch < 0) {
				$scope.filter.offset = 0;
			}
			else {
				$scope.filter.offset-= ($scope.filter.num_to_fetch * 1);
			}
			// $scope.display_from -= 30;
			// $scope.display_to -= 30;
			// $scope.searchResults();
		};

		$scope.nextResults = function () {
			if ($scope.filter.offset + ($scope.filter.num_to_fetch * 1) > $scope.totals.filter.num_transactions) {
				//stop it going past the end.
				return;
			}
			$scope.filter.offset+= ($scope.filter.num_to_fetch * 1);
			// $scope.display_from += 30;
			// $scope.display_to += 30;
			// $scope.searchResults();
		};

		$scope.getAccounts = function () {
			select.accounts().then(function (response) {
				$scope.accounts = response.data;
				$scope.new_transaction.account = $scope.accounts[0].id;
				$scope.new_transaction.from_account = $scope.accounts[0].id;
				$scope.new_transaction.to_account = $scope.accounts[0].id;
			});
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

		$scope.searchResults = function () {
			// var $transactions_limited = [];
			// $scope.counter = 0;
			// $($scope.transactions).each(function () {
			// 	$scope.counter++;
			// 	if ($scope.counter >= $scope.display_from && $scope.counter <= $scope.display_to) {
			// 		$transactions_limited.push(this);
			// 	}
			// });
			// $scope.transactions_limited = $transactions_limited;
			// $scope.getFilterTotals();
			// $scope.updateAccountDropdownsHTML();
			// $scope.getColors();
			// if ($show_allocation_popup == true) {
			// 	updateAllocationPopupHTML($allocation_popup_transaction_id);
			// }
		};

		$scope.getTags = function () {
			select.tags().then(function (response) {
				$scope.tags = response.data;
				$scope.autocomplete.tags = response.data;
			});
		};
		$scope.getTags();

		$scope.getColors = function () {
			select.colors().then(function (response) {
				$scope.colors = response.data;
			});
		};
		
		/*=================================================================================
		===================================================================================
		===================================================================================
		=================================insert=================================
		===================================================================================
		===================================================================================
		=================================================================================*/

		$scope.insertAccount = function () {
			insert.account().then(function (response) {
				$scope.getAccounts();
				$("#new_account_input").val("");
			});
		};

		$scope.insertTag = function () {
			//inserts a new tag into tags table, not into a transaction
			select.duplicateTagCheck().then(function (response) {
				var $duplicate = response.data;
				if ($duplicate > 0) {
					$("#tag-already-created").show();
				}
				else {
					insert.tag().then(function (response) {
						$scope.getTags();
					});
				}
			});
		};

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

		// $scope.checkNewTransactionMultipleBudgets = function () {
		// 	var $tags_with_budget = _.filter($scope.new_transaction.tags, function ($tag) {
		// 		return $tag.fixed_budget || $tag.flex_budget;
		// 	});

		// 	if ($tags_with_budget.length > 1) {
		// 		//new transaction has multiple budgets
		// 		$scope.new_transaction.multiple_budgets = true;
		// 	}
		// 	else {
		// 		$scope.new_transaction.multiple_budgets = false;
		// 	}
		// };

		$scope.insertTransaction = function ($keycode) {
			if ($keycode !== 13 || !$scope.errorCheck()) {
				return;
			}

			insert.transaction($scope.new_transaction).then(function (response) {
				//see if the transaction that was just entered has multiple budgets
				var $transaction = response.data.transaction;
				var $multiple_budgets = response.data.multiple_budgets;

				if ($multiple_budgets) {
					$scope.new_transaction.multiple_budgets = true;
					$scope.allocation_popup_transaction = $transaction;
				}
				else {
					$scope.new_transaction.multiple_budgets = false;
				}

				$scope.multiSearch(false, true);
				//clearing the new transaction tags
				$scope.new_transaction.tags = [];
			});
			$scope.new_transaction.dropdown = false;
		};

		// $scope.addBudgetInfo = function () {
		// 	insert.budgetInfo().then(function (response) {
		// 		$scope.totals();
		// 	});
		// };

		/*=================================================================================
		===================================================================================
		===================================================================================
		=================================update=================================
		===================================================================================
		===================================================================================
		=================================================================================*/

		$scope.updateFixedBudget = function () {
			update.budget($scope.new_fixed_budget.tag.id, 'fixed_budget', $scope.new_fixed_budget.budget).then(function (response) {
				$scope.totals();
			});
		};

		$scope.updateFlexBudget = function () {
			update.budget($scope.new_flex_budget.tag.id, 'percent', $scope.new_flex_budget.budget).then(function (response) {
				$scope.totals();
			});
		};

		$scope.removeFixedBudget = function ($tag_id, $tag_name) {
			if (confirm("remove fixed budget for " + $tag_name + "?")) {
				update.budget($tag_id, 'fixed_budget', 'NULL').then(function (response) {
					$scope.totals();
				});
			}
		};

		$scope.removeFlexBudget = function ($tag_id, $tag_name) {
			if (confirm("remove flex budget for " + $tag_name + "?")) {
				update.budget($tag_id, 'percent', 'NULL').then(function (response) {
					$scope.totals();
				});
			}
		};

		$scope.updateAccountDropdownsHTML = function () {
			// update.accountDropdownsHTML().then(function (response) {
			// 	$('.account-dropdown:not("#search-account-select, #account-select, #transfer-from-account-select, #transfer-to-account-select")').html(response);
			// 	accountValue();
			// });
		};

		$scope.updateAccountSetup = function ($account_id, $account_name) {
			$scope.edit_account.id = $account_id;
			$scope.edit_account.name = $account_name;
			$scope.show.edit_account = true;
		};

		$scope.updateAccount = function () {
			update.accountName($scope.edit_account.id, $scope.edit_account.name).then(function (response) {
				$scope.getAccounts();
				$scope.show.edit_account = false;
			});
		};

		$scope.updateReconciliation = function ($transaction_id, $reconciliation) {
			update.reconciliation($transaction_id, $reconciliation).then(function (response) {
				$scope.multiSearch();
			});
		};

		$scope.updateTagSetup = function ($tag_id, $tag_name) {
			$scope.edit_tag.id = $tag_id;
			$scope.edit_tag.name = $tag_name;
			$scope.show.edit_tag = true;
		};

		$scope.updateTag = function () {
			update.tagName($scope.edit_tag.id, $scope.edit_tag.name).then(function (response) {
				$scope.getTags();
				$scope.show.edit_tag = false;
			});
		};

		$scope.updateTagSelectHTML = function () {
			update.tagSelectHTML().then(function (response) {
				$("#fixed-budget-tag-select").html('<option>Fixed Budget</option>' + response);
				$("#flex-budget-tag-select").html('<option>Flex Budget</option>' + response);
			});
		};

		$scope.editTagName = function () {
			update.tagName().then(function (response) {
				$(".appended_tag_div li").each(function () {
					if ($(this).text() === $old_name) {
						$(this).text($new_name);
						tagString();
						saveEdit($(this).closest(".tag_container").siblings(".results_inner_div"));
					}
				});
			});
		};

		$scope.updateCSDSetup = function ($tag) {
			$scope.edit_CSD = $tag;
			$scope.show.edit_CSD = true;
		};

		$scope.updateCSD = function () {
			update.CSD($scope.edit_CSD.id, $scope.edit_CSD.CSD).then(function (response) {
				$scope.totals();
				$scope.show.edit_CSD = false;
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

		$scope.updateColors = function () {
			update.colors($scope.colors).then(function (response) {
				$scope.getColors();
				$scope.show.color_picker = false;
			});
		};

		$scope.updateTransactionSetup = function ($transaction) {
			$scope.edit_transaction = $transaction;
			$scope.show.edit_transaction = true;
		};

		$scope.updateTransaction = function () {
			var $date_entry = $("#edit-transaction-date").val();
			$scope.edit_transaction.date.user = $date_entry;
			$scope.edit_transaction.date.sql = Date.parse($date_entry).toString('yyyy-MM-dd');
			update.transaction($scope.edit_transaction).then(function (response) {
				$scope.multiSearch();
				$scope.totals();
				$scope.show.edit_transaction = false;
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
			update.massTags().then(function (response) {
				multiSearch();
				$tag_array.length = 0;
				$tag_location.html($tag_array);
			});
		};

		$scope.massEditDescription = function () {
			update.massDescription().then(function (response) {
				multiSearch();
			});
		};

		// =================================allocation=================================

		$scope.showAllocationPopup = function ($transaction) {
			$scope.show.allocation_popup = true;
			$scope.allocation_popup_transaction = $transaction;
			
			select.allocationInfo($transaction.id).then(function (response) {
				$scope.allocation_popup_transaction.allocation_totals = response.data;
			});
		};

		$scope.updateAllocation = function ($keycode, $type, $value, $tag_id) {
			if ($keycode === 13) {
				update.allocation($type, $value, $scope.allocation_popup_transaction.id, $tag_id).then(function (response) {
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
			update.allocationStatus($scope.allocation_popup_transaction.id, $scope.allocation_popup_transaction.allocation).then(function (response) {
				console.log("something");
			});
		};

		// $scope.markAsAllocated = function () {
		// 	update.markAsAllocated().then(function (response) {
		// 		multiSearch();
		// 	});
		// };

		// $scope.allocateTagBudget = function () {
		// 	update.allocateTagBudget().then(function (response) {
		// 		$scope.totals();
		// 		multiSearch();
		// 	});
		// };

		// $scope.updateAllocationPopupHTML = function () {
		// 	update.allocationPopupHTML().then(function (response) {
		// 		$scope.allocation_popup.tags = response.data;
		// 		var $tags = $response.tags;
		// 		var $fixed_sum = $response.fixed_sum;
		// 		var $percent_sum = $response.percent_sum;
		// 		var $allocation = $response.allocation;
		// 		var $type = $response.type;

		// 		$($tags).each(function () {
		// 			var $allocated_percent = this.percent;
		// 			var $allocated_fixed = this.amount;
		// 			var $current;
		// 			var $type;

		// 			if ($allocated_percent === null) {
		// 				$allocated_percent = $calculated_percent;
		// 				$current = $allocated_fixed;
		// 				$type = "$";
		// 			}

		// 			else {
		// 				$current = $allocated_percent;
		// 				$type = "%";
		// 			}
					
		// 		});
		// 	});
		// };

		// var $allocation_popup_transaction_id;
		// var $show_allocation_popup = false;

		// $("#all-transactions-wrapper").on('click', '.allocate', function () {
		// 	var $tbody = $(this).closest('tbody');
		// 	$show_allocation_popup = true;

		// 	$allocation_popup_transaction_id = $($tbody).attr('id');
			
		// 	updateAllocationPopupHTML($allocation_popup_transaction_id);
		// });

		// $("body").on('keyup', '.allocation-input', function (keypress) {
		// 	if (keypress.which === 13) {
		// 		var $tr = $(this).closest('tr');
		// 		allocateTagBudget($tr);
		// 	}
		// });

		// $("body").on('change', '.select-type', function () {
		// 	var $tr = $(this).closest('tr');
		// 	allocateTagBudget($tr);
		// });

		// $("body").on('click', '#allocate-checkbox', function () {
		// 	var $popup = $("#budget-tags-div");

		// 	if ($($popup).hasClass('new-transaction-allocation-popup')) {
		// 		// $new_transaction = true;
		// 	}
		// 	else {
		// 		markAsAllocated(this);
		// 	}
		// });

		/*=================================================================================
		===================================================================================
		===================================================================================
		=================================delete=================================
		===================================================================================
		===================================================================================
		=================================================================================*/

		$scope.deleteTag = function ($tag_id) {
			select.countTransactionsWithTag($tag_id).then(function (response) {
				var $count = response.data;
				if (confirm("You have " + $count + " transactions with this tag. Are you sure?")) {
					deleteItem.tag($tag_id).then(function (response) {
						$scope.getTags();
					});
				}
			});
		};

		$scope.deleteAccount = function ($account_id) {
			if (confirm("Are you sure you want to delete this account?")) {
				deleteItem.account($account_id).then(function (response) {
					$scope.getAccounts();
				});
			}
		};
		
		$("#search-div").on('click', '#search-tag-location li', function () {
			removeTag(this, $search_tag_array, $("#search-tag-location"), multiSearch);
		});

		$("#new-transaction-tbody").on('click', '#new-transaction-tag-location li', function () {
			removeTag(this, $transaction_tag_array, $("#new-transaction-tag-location"));
		});

		$scope.removeTag = function ($tag, $array, $scope_property) {
			$scope[$scope_property]['tags'] = _.without($array, $tag);
		};

		$scope.deleteTransaction = function ($transaction_id) {
			if (confirm("Are you sure?")) {
				deleteItem.transaction($transaction_id).then(function (response) {
					$scope.multiSearch();
					$scope.totals();
				});
			}
		};

		$("#mass-delete-button").on('click', function () {
			if (confirm("You are about to delete " + $(".checked").length + " transactions. Are you sure you want to do this?")) {
				massDelete();
			}
		});

		$(".budget").on('click', '.delete_budget', function () {
			var $tag_id = $(this).closest('.budget_info_ul').attr('data-tag-id');
			var $tag_name = $(this).prevAll(".budget-tag").text();
			if (confirm("Are you sure?")) {
				deleteBudget($tag_id, $tag_name);
			}
		});

		$scope.deleteBudget = function () {
			deleteItem.budget().then(function (response) {
				$scope.totals();
			});
		};

		/*=================================================================================
		===================================================================================
		===================================================================================
		=================================filter=================================
		===================================================================================
		===================================================================================
		=================================================================================*/

		function multiSearchDateFilter ($multiSearch_transactions, $from_date, $to_date) {
			filter.multiSearchDate();
		}

		function multiSearchTagFilter ($multiSearch_transactions, $tag_ids_searched_for) {
			filter.multiSearchTag();
		}

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
				if ($location_for_tags === $scope.new_fixed_budget.tag) {
					//We are just autocompleting the budget tag input, not adding a tag anywhere
					if (!$typing) {
						return;
					}
					$scope.autocompleteFixedBudget();
					return;
				}
				else if ($location_for_tags === $scope.new_flex_budget.tag) {
					//We are just autocompleting the budget tag input, not adding a tag anywhere
					if (!$typing) {
						return;
					}
					$scope.autocompleteFlexBudget();
					return;
				}
				//We are adding a tag
				$scope.addTagToTransaction($location_for_tags);

				//resetting the dropdown to show all the tags again after a tag has been added
				$scope.autocomplete.tags = $scope.tags;
			}
		};

		$scope.autocompleteFixedBudget = function () {
			$scope.autocomplete.tags = $scope.tags;
			$scope.new_fixed_budget.tag.id = $scope.selected.id;
			$scope.new_fixed_budget.tag.name = $scope.selected.name;
			$scope.new_fixed_budget.tag.fixed_budget = $scope.selected.fixed_budget;
			$scope.new_fixed_budget.tag.flex_budget = $scope.selected.flex_budget;
			$scope.new_fixed_budget.dropdown = false;

			if ($scope.new_fixed_budget.tag.flex_budget) {
				$scope.new_fixed_budget.tag = {};
				$scope.selected = {};
				alert("You've got a flex budget for that tag.");
				return;
			}

			$("#budget-fixed-tag-input").val($scope.selected.name);
			$("#budget-fixed-budget-input").focus();
		};

		$scope.autocompleteFlexBudget = function () {
			$scope.autocomplete.tags = $scope.tags;
			$scope.new_flex_budget.tag.id = $scope.selected.id;
			$scope.new_flex_budget.tag.name = $scope.selected.name;
			$scope.new_flex_budget.tag.fixed_budget = $scope.selected.fixed_budget;
			$scope.new_flex_budget.tag.flex_budget = $scope.selected.flex_budget;
			$scope.new_flex_budget.dropdown = false;

			if ($scope.new_flex_budget.tag.fixed_budget) {
				$scope.new_flex_budget.tag = {};
				$scope.selected = {};
				alert("You've got a fixed budget for that tag.");
				return;
			}

			$("#budget-flex-tag-input").val($scope.selected.name);
			$("#budget-flex-budget-input").focus();
		};

		$scope.filterTransactions = function ($keycode, $typing, $field) {
			//for the transaction autocomplete
			$scope.show.autocomplete.transactions = true;
			if ($keycode !== 38 && $keycode !== 40 && $keycode !== 13) {
				//not up arrow, down arrow or enter, so filter transactions
				autocomplete.removeSelected($scope.transactions);
				autocomplete.filterTransactions($typing, $field).then(function (response) {
					$scope.autocomplete.transactions = response.data;
					$scope.autocomplete.transactions = autocomplete.transferTransactions($scope.autocomplete.transactions);
				});
				
				// $scope.autocomplete.transactions = autocomplete.filterTransactions($scope.transactions, $typing, $field);
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
					$scope.autocompleteTransaction();
				}
				else {
					$scope.insertTransaction(13);
				}
			}
		};

		$scope.autocompleteTransaction = function ($selected) {
			$selected = $selected || _.find($scope.autocomplete.transactions, function ($transaction) {
				return $transaction.selected === true;
			});
			$scope.new_transaction.description = $selected.description;
			$scope.new_transaction.merchant = $selected.merchant;
			$scope.new_transaction.total = $selected.total;
			$scope.new_transaction.type = $selected.type;
			$scope.new_transaction.account = $selected.account.id;
			$scope.new_transaction.from_account = $selected.from_account.id;
			$scope.new_transaction.to_account = $selected.to_account.id;
			$scope.new_transaction.tags = $selected.tags;

			$scope.show.autocomplete = false;

			autocomplete.removeSelected($scope.transactions);
			autocomplete.removeSelected($scope.autocomplete.transactions);
		};

			
			
			
		// select.autocompleteTransaction($typing, $iterate).then(function (response) {
		// 	$scope.autocomplete.transactions = response.data;
		// 	var $transactions_without_duplicates = [];
			
		// 	$($scope.autocomplete.transactions).each(function () {
		// 		autocomplete.duplicateCheck(this, $transactions_without_duplicates);
		// 	});

		// 	$transactions_without_duplicates = autocomplete.transfers();
		// 	$scope.autocomplete.transactions = $transactions_without_duplicates;
		// });

		/*=================================================================================
		===================================================================================
		===================================================================================
		=================================watches=================================
		===================================================================================
		===================================================================================
		=================================================================================*/

		// $scope.$watchCollection('new_transaction.tags', function () {
		// 	$scope.checkNewTransactionMultipleBudgets();
		// });

		$scope.$watchCollection('colors', function (newValue) {
			$("#income-color-picker").val(newValue.income);
			$("#expense-color-picker").val(newValue.expense);
			$("#transfer-color-picker").val(newValue.transfer);
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

		// $scope.$watch('filter.num_to_fetch', function (newValue, oldValue) {
		// 	if (newValue === oldValue) {
		// 		return;
		// 	}
		// 	$scope.multiSearch(true);
		// });

		$scope.$watchGroup(['filter.offset', 'filter.num_to_fetch'], function (newValue, oldValue) {
			$scope.filter.display_from = $scope.filter.offset + 1;
			$scope.filter.display_to = $scope.filter.offset + ($scope.filter.num_to_fetch * 1);
			if (newValue === oldValue) {
				return;
			}
			$scope.multiSearch(true);
		});

		/*=================================================================================
		===================================================================================
		===================================================================================
		=================================other=================================
		===================================================================================
		===================================================================================
		=================================================================================*/

		$scope.changeTab = function ($tab) {
			$scope.tab = $tab;
		};

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

		// $scope.callFunctions = function () {
		// 	$(".my-dropdown:not(.modal .my-dropdown)").each(function () {
		// 	dropdownPosition(this);
		// 	});

		// 	$scope.updateTagSelectHTML();
		// 	$scope.updateAccountDropdownsHTML();
		// 	$scope.getAccounts();
		// 	totals();
		// };

		$scope.duplicateTagCheck = function ($tag_id, $tag_array) {
			//checks for duplicate tags when adding a new tag to an array
			for (var i = 0; i < $tag_array.length; i++) {
				if ($tag_array[i].tag_id === $tag_id) {
					return false; //it is a duplicate
				}
			}
			return true; //it is not a duplicate
		};

		/*=================================================================================
		===================================================================================
		===================================================================================
		=================================totals=================================
		===================================================================================
		===================================================================================
		=================================================================================*/

		$scope.getASR = function () {
			totals.ASR($scope.transactions_limited).then(function (response) {
				$scope.totals.filter.ASR = response.data;
			});
		};

		$scope.getFilterTotals = function () {
			totals.filterTotals($scope.transactions).then(function (response) {
				$scope.totals.filter = response.data;
				$scope.getASR();
			});
		};

		$scope.totals = function () {
			totals.basicTotals().then(function (response) {
				$scope.totals.basic = response.data;
			});
			totals.budget().then(function (response) {
				$scope.totals.budget = response.data;
			});

			

			function updateChart () {
					$(".bar_chart_li:first-child").css('height', '0%');
					$(".bar_chart_li:nth-child(2)").css('height', '0%');
					$(".bar_chart_li:first-child").css('height', getTotal()[6] + '%');
					$(".bar_chart_li:nth-child(2)").css('height', getTotal()[5] + '%');
			}
		};

		$scope.totals();

		/*==============================my plugin==============================*/

		

		/*==============================flexbox table==============================*/

		// $scope.flexboxTable = function () {
		// 	//forming the $columns array
		// 	var $columns = [];
		// 	var $column;
		// 	var $width;
		// 	var $index;
		// 	var $max_width;
		// 	//gets the number of columns in the table (assuming all other rows have the same number as the first row)
		// 	$(".flex-table .flex-table-row:first-child div").each(function () {
		// 		$column = $(this).index();
		// 		$columns.push({
		// 			index: $column,
		// 			widths: []
		// 		});
		// 	});

		// 	$(".flex-table .flex-table-row").each(function () {
				
		// 		var $row = $(this);
		// 		var $td = $(this).children('div');

		// 		$($td).each(function () {
		// 			//pushing the width of all the tds onto the $columns array
		// 			$index = $(this).index();
		// 			$width = $(this).width();

		// 			$column = _.find($columns, function ($column) {
		// 				return $column.index === $index;
		// 			});

		// 			$column.widths.push($width);

		// 		});
		// 	});

		// 	//getting the max width of each column
		// 	$($columns).each(function () {
		// 		$index = $columns.indexOf(this);
		// 		$column = this;
		// 		$widths = $column.widths;
		// 		$max_width = _.max($widths, function (width) {
		// 			return width;
		// 		});
		// 		$column.max_width = $max_width;

		// 		// $(".flex-table .flex-table-row > div").eq($index).width($max_width);
		// 		$index = $index + 1; //because nth-child doesn't use zero based
		// 		$(".flex-table .flex-table-row > div:nth-child(" + $index + ")").width($max_width);
		// 	});
		// };

		// var $num = 3;

		// $(".flex-table .total").each(function () {
		// 	var $width = $(this).width();
		// 	console.log('total width: ' + $width);
		// });

		/*==============================onload==============================*/

		$scope.multiSearch();
		$scope.getAccounts();
		$scope.getColors();

		//to go through
		
		// $scope.updateAccountDropdownsHTML();
		// $scope.getColors();
		// if ($show_allocation_popup == true) {
		// 	updateAllocationPopupHTML($allocation_popup_transaction_id);
		// }

	}); //end display controller


})();