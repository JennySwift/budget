app.factory('autocomplete', function ($http) {
	var $object = {};
	// $object.upArrow = function () {
	// 	if ($(".selected").prev().length > 0) {
	// 		//there is an item before the selected one
	// 		$(".selected").prev().addClass('selected');
	// 		$(".selected").last().removeClass('selected');
	// 	}
	// };
	// $object.downArrow = function () {
	// 	if ($(".selected").next().length > 0) {
	// 		//there is an item after the selected one
	// 		$(".selected").next().addClass('selected');
	// 		$(".selected").first().removeClass('selected');
	// 	}
	// };
	$object.upArrow = function ($array) {
		var $selected = _.find($array, function ($item) {
			return $item.selected === true;
		});
		var $index = _.indexOf($array, $selected);
		var $prev_index = $index - 1;
		var $prev_item = $array[$prev_index];

		if ($prev_item) {
			delete $selected.selected;
			$prev_item.selected = true;
		}
		return $prev_item;
	};
	$object.downArrow = function ($array) {
		var $selected = _.find($array, function ($item) {
			return $item.selected === true;
		});
		var $next_item;

		if (!$selected) {
			var $first = _.first($array);
			$first.selected = true;
		}

		else {
			var $index = _.indexOf($array, $selected);
			var $next_index = $index + 1;
			$next_item = $array[$next_index];

			if ($next_item) {
				delete $selected.selected;
				$next_item.selected = true;
			}
		}
		return $next_item;
	};
	$object.filterTags = function ($tags, $typing) {
		$filtered_tags = _.filter($tags, function ($tag) {
			return $tag.name.toLowerCase().indexOf($typing.toLowerCase()) !== -1;
		});

		return $filtered_tags;
	};
	$object.filterTransactions = function ($typing, $column) {
		var $url = 'ajax/select.php';
		var $description = 'autocomplete transaction';
		var $data = {
			description: $description,
			typing: $typing,
			column: $column
		};
		
		return $http.post($url, $data);
	};
	// $object.filterTransactions = function ($transactions, $typing, $field) {
	// 	$transactions = _.filter($transactions, function ($transaction) {
	// 		if ($field === 'description') {
	// 			return $transaction.description.toLowerCase().indexOf($typing.toLowerCase()) !== -1;
	// 		}
	// 		else if ($field === 'merchant') {
	// 			return $transaction.merchant.toLowerCase().indexOf($typing.toLowerCase()) !== -1;
	// 		}
	// 	});
	// 	$object.removeSelected($transactions);
	// 	//limiting the transactions in the autocomplete so it's faster
	// 	$transactions = _.filter($transactions, function ($transaction) {
	// 		return $transactions.indexOf($transaction) < 20;
	// 	});

	// 	if ($typing !== "") {
	// 		$object.selectFirstItem($transactions);
	// 	}

	// 	return $transactions;
	// };
	$object.removeSelected = function ($array) {
		var $first = _.first($array);
		//removing the previous selected, in case the input is focused again after already setting the selected transaction
		var $selected = _.find($array, function ($item) {
			return $item.selected === true;
		});
		if ($selected) {
			//if it's the first time the input is focused, $selected won't exist yet
			delete $selected.selected;
		}
		//resetting the selected transaction to the first one
		// $first.selected = true;
	};
	$object.selectFirstItem = function ($array) {
		var $first = _.first($array);
		$first.selected = true;
		return $first;
	};
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