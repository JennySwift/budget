app.factory('totals', function ($http) {
	return {
		basicTotals: function () {
			var $url = 'totals/basic';
			var $description = 'basic totals';
			var $data = {
				description: $description
			};
			
			return $http.post($url, $data);
		},
		budget: function () {
			var $url = 'totals/budget';
			var $description = 'budget totals';
			var $data = {
				description: $description
			};
			
			return $http.post($url, $data);
		},
		filterTotals: function ($transactions) {
			var $url = 'totals/filter';
			var $description = 'get filter totals';
			// var $stringified_transactions = JSON.stringify($transactions);
			var $data = {
				description: $description,
				// transactions: $stringified_transactions
				transactions: $transactions
			};
			
			return $http.post($url, $data);
		},
		ASR: function ($transactions) {
			var $transactions_clone = [];
			$($transactions).each(function () {
				$transactions_clone.push(this);
			});

			$($transactions_clone).each(function () {
				var $reconciled = this.reconciled;
				var $total = this.total;
				var $index = $transactions_clone.indexOf(this);
				if ($reconciled === "not_reconciled") {
					$transactions_clone.splice($index, 1);
				}
			});

			// $transactions_clone = JSON.stringify($transactions_clone);

			var $url = 'totals/ASR';
			var $description = 'ASR';
			var $data = {
				description: $description,
				transactions: $transactions_clone
			};
			
			return $http.post($url, $data);
		}
	};
		
});