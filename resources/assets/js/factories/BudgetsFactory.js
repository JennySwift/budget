app.factory('BudgetsFactory', function ($http) {
	return {
		getAllocationTotals: function ($transaction_id) {
			var $url = 'api/select/allocationTotals';
			var $data = {
				transaction_id: $transaction_id
			};
			
			return $http.post($url, $data);
		},

        removeBudget: function ($tag) {
            var $url = 'api/remove/budget';
            var $data = {
                tag_id: $tag.id,
            };

            return $http.post($url, $data);
        },

		updateAllocation: function ($type, $value, $transaction_id, $tag_id) {
			var $url = 'api/update/allocation';
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
			var $url = 'api/update/allocationStatus';
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
        create: function ($budget, $type) {
            var $url = '/api/budgets';

            var $data = {
                type: $type,
				name: $budget.name,
                amount: $budget.amount,
                starting_date: $budget.sql_starting_date
            };

            return $http.post($url, $data);
        },

	};
});