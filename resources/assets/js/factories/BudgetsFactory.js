app.factory('BudgetsFactory', function ($http) {
	return {

        removeBudget: function ($tag) {
            var $url = 'api/remove/budget';
            var $data = {
                tag_id: $tag.id,
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