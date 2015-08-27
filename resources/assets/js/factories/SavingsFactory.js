app.factory('SavingsFactory', function ($http) {
	return {
		updateSavingsTotal: function () {
			var $amount = $("#edited-savings-total").val().replace(',', '');
			var $url = '/api/savings/updateSavingsTotal';
			var $data = {
				amount: $amount
			};
			
			return $http.put($url, $data);
		},
		addFixedToSavings: function () {
			var $amount_to_add = $("#add-fixed-to-savings").val();
			var $url = '/api/savings/addFixedToSavings';
			var $data = {
				amount_to_add: $amount_to_add
			};
			$("#add-fixed-to-savings").val("");
			
			return $http.put($url, $data);
		},
		addPercentageToSavings: function () {
			var $percentage_of_RB = $("#add-percentage-to-savings").val();
			var $url = '/api/savings/addPercentageToSavings';
			var $data = {
				percentage_of_RB: $percentage_of_RB,
			};
			$("#add-percentage-to-savings").val("");
			
			return $http.put($url, $data);
		},
		addPercentageToSavingsAutomatically: function ($amount_to_add) {
			var $url = '/api/savings/addPercentageToSavingsAutomatically';
			var $data = {
				amount_to_add: $amount_to_add
			};
			
			return $http.put($url, $data);
		},
		reverseAutomaticInsertIntoSavings: function ($amount_to_subtract) {
			var $url = '/api/savings/reverseAutomaticInsertIntoSavings';
			var $data = {
				amount_to_subtract: $amount_to_subtract
			};
			
			return $http.put($url, $data);
		}
	};
});