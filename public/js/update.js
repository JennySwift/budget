app.factory('update', function ($http) {
	return {
		savingsTotal: function () {
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
		},
		budget: function ($tag_id, $column, $budget) {
			var $url = 'update/budget';
			var $description = 'budget';
			var $data = {
				description: $description,
				tag_id: $tag_id,
				column: $column,
				budget: $budget
			};
			
			return $http.post($url, $data);
		},
		tagName: function ($tag_id, $tag_name) {
			var $url = 'update/tagName';
			var $description = 'tag name';
			var $data = {
				description: $description,
				tag_id: $tag_id,
				tag_name: $tag_name
			};
			
			return $http.post($url, $data);
			
		},
		accountName: function ($account_id, $account_name) {
			var $url = 'update/accountName';
			var $description = 'account name';
			var $data = {
				description: $description,
				account_id: $account_id,
				account_name: $account_name
			};
			
			return $http.post($url, $data);
			
		},
		// =============================allocation=============================
		allocation: function ($type, $value, $transaction_id, $tag_id) {
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
		allocationStatus: function ($transaction_id, $status) {
			var $url = 'update/allocationStatus';
			var $data = {
				transaction_id: $transaction_id,
				status: $status
			};
			
			return $http.post($url, $data);
		},

		// allocateTagBudget: function ($tr) {
		// 	var $transaction_id = $("#budget-allocation-table").attr('data-transaction-id');
		// 	var $input = $($tr).find('.allocation-input');
		// 	var $tag_id = $($tr).attr('data-tag-id');
		// 	var $select_type = $($tr).find('.select-type');
		// 	var $type = $($select_type).val();
		// 	var $new_transaction = false;
		// 	var $value = $($input).val();

		// 	if ($("#budget-allocation-table").hasClass('expense') && $type === "$" && $value > 0) {
		// 		$value = $value * -1;
		// 	}

		// 	var $url = 'ajax/update.php';
		// 	var $description = 'set transaction tag budget';
		// 	var $data = {
		// 		description: $description,
		// 		transaction_id: $transaction_id,
		// 		tag_id: $tag_id,
		// 		value: $value,
		// 		type: $type
		// 	};
			
		// 	return $http.post($url, $data);
		// },
		// updateAllocationPopupHTML: function ($transaction_id) {
		// 	var $transaction_total = $("#" + $transaction_id).find('.total').val();

		// 	var $url = 'ajax/update.php';
		// 	var $description = 'allocation popup';
		// 	var $data = {
		// 		description: $description,
		// 		transaction_id: $transaction_id,
		// 		transaction_total: $transaction_total
		// 	};
			
		// 	return $http.post($url, $data);
		// },
		// markAsAllocated: function ($this) {
		// 	var $transaction_id = $("#budget-allocation-table").attr('data-transaction-id');
		// 	var $action;
		// 	var $id = $($this).attr('id');
		// 	var $checked = $("#allocate-checkbox:checked");

		// 	if ($checked.length > 0) {
		// 		//it is checked
		// 		$action = "mark as allocated";
		// 	}
		// 	else {
		// 		//it is unchecked
		// 		$action = "mark as not allocated";
		// 	}

		// 	var $url = 'ajax/update.php';
		// 	var $description = 'update allocation';
		// 	var $data = {
		// 		description: $description,
		// 		transaction_id: $transaction_id,
		// 		action: $action
		// 	};
			
		// 	return $http.post($url, $data);
		// },
		// =============================end allocation=============================
		massTags: function ($tag_array, $url, $tag_location) {
			var $transaction_id;

			var $tag_id_array = $tag_array.map(function(el) {
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
		},
		massEditDescription: function () {
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
		},
		startingDate: function () {
			if (keypress.which === 13) {
				var $tag_id = $(this).closest('.budget_info_ul').attr('data-tag-id');
				var $starting_date = $(this).val();

				$starting_date = Date.parse($starting_date);
				$starting_date = $starting_date.toString('yyyy-MM-dd');

				var $url = 'update/startingDate';
				var $description = '';
				var $data = {
					description: $description,
					tag_id: $tag_id,
					starting_date: $starting_date
				};
				
				return $http.post($url, $data);
			}
		},
		CSD: function ($tag_id, $CSD) {
			$CSD = Date.parse($CSD).toString('yyyy-MM-dd');
			var $url = 'update/CSD';
			var $description = 'CSD';
			var $data = {
				description: $description,
				tag_id: $tag_id,
				CSD: $CSD
			};
			
			return $http.post($url, $data);
		},
		colors: function ($colors) {
			var $url = 'update/colors';
			var $description = 'colors';
			var $data = {
				description: $description,
				colors: $colors
			};
			
			return $http.post($url, $data);
		},
		transaction: function ($transaction) {
			var $url = 'update/transaction';
			var $description = 'transaction';
			var $data = {
				description: $description,
				transaction: $transaction
			};
				
			return $http.post($url, $data);
		},
		reconciliation: function ($transaction_id, $reconciled) {
			var $url = 'update/reconciliation';

			if ($reconciled === true) {
				$reconciled = 'true';
			}
			else {
				$reconciled = 'false';
			}

			var $data = {
				id: $transaction_id,
				reconciled: $reconciled
			};
			
			return $http.post($url, $data);
		}
	};
});

// $("#display_tags, #display_accounts").on('click', '.edit_tag, .edit_account', function (keypress) {
// 	console.log("running anonymouseditTagName");
// 	var $id = $(this).parent().attr('id');
// 	var $old_name = $(this).siblings(".tag_name, .account_name").val();
// 	if ($(this).parent().hasClass("display_tags_mini_div")) {
// 		$url = 'ajax/update.php';
// 	}
// 	else {
// 		$url = 'ajax/update.php';
// 	}

// 	var $new_name = prompt("enter new name");

// 	if ($new_name) {
// 		console.log("true");
// 		$(this).siblings(".tag_name, .account_name").val($new_name);
// 		editTagName($id, $url, $old_name, $new_name);
// 	}
// 	else {
// 		console.log("false");
// 	}
// });