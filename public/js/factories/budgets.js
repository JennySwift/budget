app.factory('budgets', function ($http) {
	return {
		/**
		 * select
		 */
		
		getAllocationInfo: function ($transaction_id) {
			var $url = 'select/allocationInfo';
			var $description = 'allocation info';
			var $data = {
				description: $description,
				transaction_id: $transaction_id
			};
			
			return $http.post($url, $data);
		},
		getAllocationTotals: function ($transaction_id) {
			var $url = 'select/allocationTotals';
			var $data = {
				transaction_id: $transaction_id
			};
			
			return $http.post($url, $data);
		},
		
		/**
		 * insert
		 */
		
		/**
		 * update
		 */
		
		updateBudget: function ($tag_id, $column, $budget) {
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
                //description: 'CSD',
                tag: $tag,
                CSD: Date.parse($tag.CSD).toString('yyyy-MM-dd')
            };
            
            return $http.put($url, $data);
		},

		/**
		 * delete
		 */
		
		insertFlexBudget: function () {
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
				var $url = 'insert/flexBudget';
				var $description = 'flex budget';
				var $data = {
					description: $description,
					tag: $tag,
					percent: $percent
				};
				
				return $http.post($url, $data);
			}
		},
		insertBudgetInfo: function () {
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

				var $url = 'insert/budgetInfo';
				var $description = 'fixed budget';
				var $data = {
					description: $description,
					budget_input_value: $budget_input_value,
					budget_select_value: $budget_select_value
				};
				
				return $http.post($url, $data);
			}
		},
	};
});