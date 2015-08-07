app.factory('BudgetsFactory', function ($http) {
	return {
		getAllocationTotals: function ($transaction_id) {
			var $url = 'select/allocationTotals';
			var $data = {
				transaction_id: $transaction_id
			};
			
			return $http.post($url, $data);
		},
		
		updateBudget: function ($tag, $column) {
			var $url = 'update/budget';
			var $data = {
				tag_id: $tag.id,
				column: $column,
				budget: $tag.budget,
                starting_date: $tag.sql_starting_date
			};
			
			return $http.post($url, $data);
		},

        removeBudget: function ($tag) {
            var $url = 'remove/budget';
            var $data = {
                tag_id: $tag.id,
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
                tag: $tag,
                CSD: Date.parse($tag.formatted_starting_date).toString('yyyy-MM-dd')
            };
            
            return $http.put($url, $data);
		}
	};
});