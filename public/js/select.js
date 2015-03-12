app.factory('select', function ($http) {
	return {
		colors: function () {
			var $url = 'select/colors';
			var $description = 'colors';
			var $data = {
				description: $description,
			};
			
			return $http.post($url, $data);
		},
		tags: function () {
			var $url = 'select/tags';
			var $description = 'tags';
			var $data = {
				description: $description
			};
			
			return $http.post($url, $data);
		},
		duplicateTagCheck: function () {
			var $url = 'select/duplicate-tag-check';
			var $description = 'duplicate tag check';
			var $new_tag_name = $("#new_tag_input").val();
			var $data = {
				description: $description,
				new_tag_name: $new_tag_name
			};
			
			return $http.post($url, $data);
		},
		countTransactionsWithTag: function ($tag_id) {
			var $url = 'select/countTransactionsWithTag';
			var $description = 'count transactions with tag';
			var $data = {
				description: $description,
				tag_id: $tag_id
			};
			
			return $http.post($url, $data);
		},
		accounts: function () {
			var $url = 'select/accounts';
			var $description = 'accounts';
			var $data = {
				description: $description
			};
			
			return $http.post($url, $data);
		},
		allocationInfo: function ($transaction_id) {
			var $url = 'select/allocationInfo';
			var $description = 'allocation info';
			var $data = {
				description: $description,
				transaction_id: $transaction_id
			};
			
			return $http.post($url, $data);
		},
		allocationTotals: function ($transaction_id) {
			var $url = 'select/allocationTotals';
			var $data = {
				transaction_id: $transaction_id
			};
			
			return $http.post($url, $data);
		},
		multiSearch: function ($filter, $reset) {
			// if ($reset) {
			// 	$display_from = 1;
			// 	$display_to = 30;
			// }

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
		}
	};
});