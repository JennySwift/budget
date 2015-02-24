app.factory('insert', function ($http) {
	return {
		tag: function () {
			//adds a new tag to tags table, not to a transaction
			var $url = 'insert/tag';
			var $description = 'tag';
			var $new_tag_name = $(".new_tag_input").val();
			var $data = {
				description: $description,
				new_tag_name: $new_tag_name
			};
			$("#tag-already-created").hide();
			
			return $http.post($url, $data);
		},
		addResultTag: function ($this) {
			var $transaction = $($this).closest("tbody");
			var $transaction_id = $($transaction).attr('id');
			var $tag = $($transaction).find(".select").text();
			var $tag_id = $($transaction).find(".select").attr('data-id');
			var $url = 'ajax/insert.php';
			var $description = 'result tag';
			var $data = {
				description: $description,
				transaction_id: $transaction_id,
				tag_id: $tag_id
			};
			
			return $http.post($url, $data);
		},
		account: function () {
			var $url = 'ajax/insert.php';
			var $description = 'account';
			var $name = $(".new_account_input").val();
			var $data = {
				description: $description,
				name: $name
			};
			
			return $http.post($url, $data);
		},
		flexBudget: function () {
			$("#fixed_budget_used").hide();
			var $exists = "";
			var $percent = $(".perc_budget_input").val()*1;
			var $tag = $("#flex-budget-tag-select").val();
			$("#fixed-budget-info-table .budget-tag").each(function () {
				if ($(this).text() === $tag) {
					$exists = true;
				}
			});
			if ($exists === true) {
				console.log("exists: " + $exists);
				$("#flex_budget_used").show();
			}
			else {
				$("#flex_budget_used").hide();
				var $url = 'insert.php';
				var $description = 'flex budget';
				var $data = {
					description: $description,
					tag: $tag,
					percent: $percent
				};
				
				return $http.post($url, $data);
			}
		},
		budgetInfo: function () {
			$("#flex_budget_used").hide();
			var $exists = "";
			$budget_input_value = $("#add_budget_input").val();
			$budget_select_value = $("#fixed-budget-tag-select").val();

			$("#flex-budget-info-table .budget-tag").each(function () {
				if ($(this).text() === $budget_select_value) {
					$exists = true;
				}
			});
			if ($exists === true && $("#add_budget_input, #fixed-budget-tag-select").is(":focus")) {
				console.log("exists: " + $exists);
				$("#fixed_budget_used").show();
			}
			else {
				$("#fixed_budget_used").hide();

				var $url = 'insert.php';
				var $description = 'fixed budget';
				var $data = {
					description: $description,
					budget_input_value: $budget_input_value,
					budget_select_value: $budget_select_value
				};
				
				return $http.post($url, $data);
			}
		},
		transaction: function ($new_transaction) {
			var $url = 'ajax/insert.php';
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
				new_transaction: $new_transaction
			};
			
			return $http.post($url, $data);
		}
	};
});