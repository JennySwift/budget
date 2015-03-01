app.factory('filter', function ($http) {
	return {
		// tags: function ($transactions, $tags_searched_for) {
		// 	$($transactions).each(function () {
		// 		var $tags = this.tags;
		// 		var $transaction_id = this.id; //for debugging purposes
		// 		var $tag_match_counter = 0;
		// 		var $transaction_index = $transactions.indexOf(this);
		// 		var $number_of_tags_searched_for = $tags_searched_for.length;
		// 		for (var i = 0; i < $tags_searched_for.length; i++) {
		// 			var $tag_id_searched_for = $tags_searched_for[i].id;
		// 			$($tags).each(function () {
		// 				var $tag_id_of_transaction = this.id;
		// 				if ($tag_id_searched_for == $tag_id_of_transaction) {
		// 					$tag_match_counter++;
		// 				}
		// 			});
		// 		}
				
		// 		if ($tag_match_counter !== $number_of_tags_searched_for) {
		// 			//get rid of the transaction if it doesn't have all the tags that were searched for
		// 			$transactions.splice($transaction_index, 1);
		// 		}
		// 	});
		// 	return $transactions;
		// },
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
	};
});