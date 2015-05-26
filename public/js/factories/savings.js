app.factory('savings', function ($http) {
	return {
		/**
		 * select
		 */
		
		/**
		 * insert
		 */
		
		/**
		 * update
		 */
		
		updateSavingsTotal: function () {
			var $amount = $("#edited-savings-total").val().replace(',', '');
			var $url = 'update/savingsTotal';
			var $data = {
				amount: $amount
			};
			
			return $http.post($url, $data);
		},
		addFixedToSavings: function () {
			var $amount_to_add = $("#add-fixed-to-savings").val();
			var $url = 'update/addFixedToSavings';
			var $data = {
				amount_to_add: $amount_to_add
			};
			$("#add-fixed-to-savings").val("");
			
			return $http.post($url, $data);
		},
		addPercentageToSavings: function () {
			var $percentage_of_RB = $("#add-percentage-to-savings").val();
			var $url = 'update/addPercentageToSavings';
			var $data = {
				percentage_of_RB: $percentage_of_RB,
			};
			$("#add-percentage-to-savings").val("");
			
			return $http.post($url, $data);
		},
		addPercentageToSavingsAutomatically: function ($amount_to_add) {
			var $url = 'update/addPercentageToSavingsAutomatically';
			var $data = {
				amount_to_add: $amount_to_add
			};
			
			return $http.post($url, $data);
		},
		reverseAutomaticInsertIntoSavings: function ($amount_to_subtract) {
			var $url = 'update/reverseAutomaticInsertIntoSavings';
			var $data = {
				amount_to_subtract: $amount_to_subtract
			};
			
			return $http.post($url, $data);
		},
		
		/**
		 * delete
		 */

	};
});