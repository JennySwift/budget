export default {

	updateSavingsTotal: function () {
		var $amount = $("#edited-savings-total").val().replace(',', '');
		var $url = '/api/savings/set';
		var $data = {
			amount: $amount
		};

		return $http.put($url, $data);
	},

	addFixedToSavings: function () {
		var $amount_to_add = $("#add-fixed-to-savings").val();
		var $url = '/api/savings/increase';
		var $data = {
			amount: $amount_to_add
		};
		$("#add-fixed-to-savings").val("");

		return $http.put($url, $data);
	},

	addPercentageToSavings: function () {
		var $percentage_of_RB = $("#add-percentage-to-savings").val();
		var $url = '/api/savings/increase';
		var $data = {
			amount: $percentage_of_RB,
		};
		$("#add-percentage-to-savings").val("");

		return $http.put($url, $data);
	}
}