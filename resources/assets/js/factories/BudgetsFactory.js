app.factory('BudgetsFactory', function ($http) {
	return {
		getAllocationTotals: function ($transaction_id) {
			var $url = 'select/allocationTotals';
			var $data = {
				transaction_id: $transaction_id
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
		update: function ($tag, $type) {
            var $url = $tag.path;

            var $data = {
                tag_id: $tag.id,
                column: $type + '_budget',
                budget: $tag[$type + '_budget'],
                starting_date: $tag.sql_starting_date
            };
            
            return $http.put($url, $data);
		},
        create: function ($tag, $type) {
            var $url = $tag.path;

            var $data = {
                tag_id: $tag.id,
                column: $type + '_budget',
                budget: $tag.budget,
                starting_date: $tag.sql_starting_date
            };

            return $http.put($url, $data);
        },

	};
});