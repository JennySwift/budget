app.factory('filter', function ($http) {
	return {
		multiSearchDate: function () {
			$("#invalid_to_date").hide();
			$("#invalid_from_date").hide();

			$($multiSearch_transactions).each(function () {
				var $date = this.date;
				var $index = $multiSearch_transactions.indexOf(this);
				
				if ($from_date !== "") {
					//showing error message				
					if (Date.parse($from_date) === null) {
						$("#invalid_from_date").show();
					}

					if (Date.parse($date).compareTo(Date.parse($from_date)) >=0) {
						// console.log($date + " is greater than: " + $from_date + "Should be visible.");
					}
					else {
						// console.log($date + " is less than: " + $from_date + " Should now be hidden.");
						$multiSearch_transactions.splice($index, 1);
					}
				}
				
				if ($to_date !== "") {
					//showing error message
					if (Date.parse($to_date) === null) {
						$("#invalid_to_date").show();
					}

					if (Date.parse($date).compareTo(Date.parse($to_date)) <=0) {
						// console.log($date + " is less than: " + $to_date + " Should be visible.");
					}
					else {
						// console.log($date + " is greater than: " + $to_date + " Should now be hidden.");
						$multiSearch_transactions.splice($index, 1);
					}
				}
			});
		},
		tags: function ($transactions, $tags_searched_for) {
			$($transactions).each(function () {
				var $tags = this.tags;
				var $transaction_id = this.id; //for debugging purposes
				var $tag_match_counter = 0;
				var $transaction_index = $transactions.indexOf(this);
				var $number_of_tags_searched_for = $tags_searched_for.length;
				for (var i = 0; i < $tags_searched_for.length; i++) {
					var $tag_id_searched_for = $tags_searched_for[i].id;
					$($tags).each(function () {
						var $tag_id_of_transaction = this.id;
						if ($tag_id_searched_for == $tag_id_of_transaction) {
							$tag_match_counter++;
						}
					});
				}
				
				if ($tag_match_counter !== $number_of_tags_searched_for) {
					//get rid of the transaction if it doesn't have all the tags that were searched for
					$transactions.splice($transaction_index, 1);
				}
			});
			return $transactions;
		},
		budget: function ($transactions, $budget_filter) {
			if ($budget_filter === "all") {
				return $transactions;
			}
			else if ($budget_filter === "single") {

			}
			else if ($budget_filter === "multiple") {
				$transactions = _.filter($transactions, function ($transaction) {
					return $transaction.multiple_budgets;
				});
			}
			return $transactions;
		}
		// multiSearchBudget: function () {
		// 	if ($("#multiple-budgets:checked").length > 0) {
		// 		//it is checked
		// 		$($multiSearch_transactions).each(function () {
		// 			var $transaction_id = this.id;
		// 			var $tags = this.tags;
		// 			var $budget_counter = 0;
		// 			var $index = $multiSearch_transactions.indexOf(this);

		// 			$($tags).each(function () {
		// 				var $tag_id = this.tag_id;
		// 				var $tag_name = this.tag_name;
		// 				var $budget = this.budget;
		// 				var $percent = this.percent;

		// 				if ($budget != null || $percent != null) {
		// 					//the tag has a budget
		// 					$budget_counter++;
		// 				}
		// 				else {
		// 					//the tag does not have a budget
		// 				}
		// 			});
		// 			if ($budget_counter > 1) {
		// 				//the transaction has more than one tag associated with a budget
		// 			}
		// 			else {
		// 				//the transaction does not have more than one tag associated with a budget.
		// 				//remove transaction from array because the multiple budget filter is on.
		// 				$multiSearch_transactions.splice($index, 1);
		// 			}
		// 		});
		// 	}
		// 	else {
		// 		//it is not checked
		// 	}
		// }
	};
});