var app = angular.module('budgetApp');

(function () {

	// ===========================display controller===========================

	app.controller('budget', function ($scope, $http, autocomplete, totals, budgets, savings, settings, transactions) {

		/**
		 * select
		 */
		
		/**
		 * insert
		 */
		
		/**
		 * update
		 */
		
		$scope.updateFixedBudget = function ($keycode) {
			if ($keycode !== 13) {
				return;
			}
			update.budget($scope.new_fixed_budget.tag.id, 'fixed_budget', $scope.new_fixed_budget.budget).then(function (response) {
				$scope.getTotals();
				//unselect the tag in the dropdown
				_.findWhere($scope.tags, {selected: true}).selected = false;
				//clear the tag inputs and focus the correct input
				$scope.new_fixed_budget.tag.name = "";
				$scope.new_fixed_budget.budget = "";
				$("#budget-fixed-tag-input").focus();
			});
		};

		$scope.updateFlexBudget = function ($keycode) {
			if ($keycode !== 13) {
				return;
			}
			update.budget($scope.new_flex_budget.tag.id, 'flex_budget', $scope.new_flex_budget.budget).then(function (response) {
				$scope.getTotals();
				//unselect the tag in the dropdown
				_.findWhere($scope.tags, {selected: true}).selected = false;
				//clear the tag inputs and focus the correct input
				$scope.new_flex_budget.tag.name = "";
				$scope.new_flex_budget.budget = "";
				$("#budget-flex-tag-input").focus();
			});
		};

		$scope.removeFixedBudget = function ($tag_id, $tag_name) {
			if (confirm("remove fixed budget for " + $tag_name + "?")) {
				update.budget($tag_id, 'fixed_budget', 'NULL').then(function (response) {
					$scope.getTotals();
				});
			}
		};

		$scope.removeFlexBudget = function ($tag_id, $tag_name) {
			if (confirm("remove flex budget for " + $tag_name + "?")) {
				update.budget($tag_id, 'flex_budget', 'NULL').then(function (response) {
					$scope.getTotals();
				});
			}
		};

		$scope.updateCSDSetup = function ($tag) {
			$scope.edit_CSD = $tag;
			$scope.show.edit_CSD = true;
		};

		$scope.updateCSD = function () {
			update.CSD($scope.edit_CSD.id, $scope.edit_CSD.CSD).then(function (response) {
				$scope.getTotals();
				$scope.show.edit_CSD = false;
			});
		};

		/**
		 * delete
		 */
		
		/**
		 * autocomplete
		 */
		
		$scope.autocompleteFixedBudget = function () {
			$scope.autocomplete.tags = $scope.tags;
			$scope.new_fixed_budget.tag.id = $scope.selected.id;
			$scope.new_fixed_budget.tag.name = $scope.selected.name;
			$scope.new_fixed_budget.tag.fixed_budget = $scope.selected.fixed_budget;
			$scope.new_fixed_budget.tag.flex_budget = $scope.selected.flex_budget;
			$scope.new_fixed_budget.dropdown = false;

			if ($scope.new_fixed_budget.tag.flex_budget) {
				$scope.new_fixed_budget.tag = {};
				$scope.selected = {};
				alert("You've got a flex budget for that tag.");
				return;
			}

			$("#budget-fixed-tag-input").val($scope.selected.name);
			$("#budget-fixed-budget-input").focus();
		};

		$scope.autocompleteFlexBudget = function () {
			$scope.autocomplete.tags = $scope.tags;
			$scope.new_flex_budget.tag.id = $scope.selected.id;
			$scope.new_flex_budget.tag.name = $scope.selected.name;
			$scope.new_flex_budget.tag.fixed_budget = $scope.selected.fixed_budget;
			$scope.new_flex_budget.tag.flex_budget = $scope.selected.flex_budget;
			$scope.new_flex_budget.dropdown = false;

			if ($scope.new_flex_budget.tag.fixed_budget) {
				$scope.new_flex_budget.tag = {};
				$scope.selected = {};
				alert("You've got a fixed budget for that tag.");
				return;
			}

			$("#budget-flex-tag-input").val($scope.selected.name);
			$("#budget-flex-budget-input").focus();
		};
		


		
	}); //end controller

})();