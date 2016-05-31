var AutocompleteRepository = {

	duplicateCheck: function ($this, $transactions_without_duplicates) {
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
	},

	/**
	 * For the transaction autocomplete
	 * @param transactions
	 * @returns {*}
     */
	removeDuplicates: function (transactions) {
		for (var i = 0; i < transactions.length; i++) {
			var transaction = transactions[i];

			var object1 = {
				description: transaction.description,
				merchant: transaction.merchant,
				total: transaction.total,
				type: transaction.type,
				account: transaction.account
			};

			// We have the properties that we don't want to be duplicates in an object.
			// Now we loop through the array again to make another object, then we can compare if the two objects are equal.
			for (var j = 0; j < transactions.length; j++) {
				var t = transactions[j];
				var index = transactions.indexOf(t);

				var object2 = {};

				if (t.id !== transaction.id && t.type === transaction.type) {
					//they are the same type, and not the same transaction
					object2 = {
						description: t.description,
						merchant: t.merchant,
						total: t.total,
						type: t.type,
						account: t.account
					};
				}

				if (_.isEqual(object1, object2)) {
					transactions.splice($index, 1);
				}
			}
		}

		return transactions;
	},

	// transferTransactions: function ($transactions) {
	// 	var $counter = 0;
	// 	var $from_account;
	// 	var $to_account;
	// 	var $total;
    //
	// 	$($transactions).each(function () {
	// 		var $index = $transactions.indexOf(this);
	// 		if (this.type === "transfer") {
	// 			$counter++;
	// 			if (this.total < 0) {
	// 				//this is a negative transfer
	// 				$from_account = this.account;
	// 			}
	// 			else if (this.total > 0) {
	// 				//this is a positive transfer
	// 				$to_account = this.account;
	// 				$total = this.total;
	// 			}
	// 			if ($counter % 2 === 1) {
	// 				//remove every second transfer transaction from the array
	// 				$transactions.splice($index, 1);
	// 			}
	// 			else if ($counter % 2 === 0) {
	// 				//keep the first of every second transfer transaction and combine the two transfers into one
	// 				this.from_account = $from_account;
	// 				this.to_account = $to_account;
	// 				this.account = {};
	// 				//so the total is positive
	// 				this.total = $total;
	// 			}
	// 		}
	// 	});
	// 	return $transactions;
	// }
};