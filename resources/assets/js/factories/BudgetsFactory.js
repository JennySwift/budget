app.factory('BudgetsFactory', function ($http) {
	return {

        insert: function ($budget) {
            var $url = '/api/budgets';

            var $data = {
                type: $budget.type,
                name: $budget.name,
                amount: $budget.amount,
                starting_date: $budget.sql_starting_date
            };

            return $http.post($url, $data);
        },

		update: function ($budget) {
            var $url = $budget.path;

            var $data = {
                id: $budget.id,
                name: $budget.name,
                type: $budget.type,
                amount: $budget.amount,
                starting_date: $budget.sqlStartingDate
            };
            
            return $http.put($url, $data);
		},

        destroy: function ($budget) {
            var $url = '/api/budgets/'+$budget.id;

            return $http.delete($url);
        }

	};
});