app.factory('transactions', function ($http) {
	return {
		/**
		 * select
		 */
		
		multiSearch: function ($filter, $reset) {
			if ($filter.single_date) {
				$filter.single_date_sql = Date.parse($filter.single_date).toString('yyyy-MM-dd');
			}
			if ($filter.from_date) {
				$filter.from_date_sql = Date.parse($filter.from_date).toString('yyyy-MM-dd');
			}
			if ($filter.to_date) {
				$filter.to_date_sql = Date.parse($filter.to_date).toString('yyyy-MM-dd');
			}

			var $url = 'select/filter';
			var $data = {
				description: 'filter',
				filter: $filter
			};

			return $http.post($url, $data);
		},

		/**
		 * insert
		 */
		
		insertTransaction: function ($new_transaction) {
			var $url = 'insert/transaction';
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
		},

		/**
		 * update
		 */
		
		updateMassTags: function ($tag_array, $url, $tag_location) {
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
		updateTransaction: function ($transaction) {
            var $url = $transaction.path;

            //Make sure total is negative for an expense transaction
            if ($transaction.type === 'expense' && $transaction.total > 0) {
                $transaction.total = $transaction.total * -1;
            }

            var $data = {
                description: 'transaction',
                transaction: $transaction
            };

            return $http.put($url, $data);
		},
		updateReconciliation: function ($transaction_id, $reconciled) {
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
		},

		/**
		 * delete
		 */

		deleteTransaction: function ($transaction_id) {
			var $url = 'delete/transaction';
				var $description = 'transaction';
				var $data = {
					description: $description,
					transaction_id: $transaction_id
				};
				
				return $http.post($url, $data);
		},
		massDelete: function () {
			$(".checked").each(function () {
				deleteTransaction($(this));
			});
		}
	};
});
