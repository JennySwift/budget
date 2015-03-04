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
		var $url = 'select/autocompleteTransaction';
		var $description = 'autocomplete transaction';
		var $data = {
			description: $description,
			typing: $typing,
			column: $column
		};
		
		return $http.post($url, $data);
	};
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