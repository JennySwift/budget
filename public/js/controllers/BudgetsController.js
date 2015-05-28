var app = angular.module('budgetApp');

(function () {

	// ===========================display controller===========================

	app.controller('budgets', function ($scope, $http, budgets, totals, autocomplete, settings) {

		/**
		 * scope properties
		 */
		
		$scope.totals = {};
		$scope.show = {
			basic_totals: true,
			budget_totals: true
		};
		$scope.autocomplete = {};

		$scope.new_fixed_budget = {
			tag: {
				name: "",
				id: ""
			},
			budget: "",
			dropdown: false
		};

		$scope.new_flex_budget = {
			tag: {
				name: "",
				id: ""
			},
			budget: "",
			dropdown: false
		};

		/**
		 * select
		 */
		
		$scope.getTags = function () {
			settings.getTags().then(function (response) {
				$scope.tags = response.data;
				$scope.autocomplete.tags = response.data;
			});
		};

		/**
		 * filter
		 */
		
		/**
		 * Almost duplicate of filterTags in controller.js
		 * @param  {[type]} $keycode           [description]
		 * @param  {[type]} $typing            [description]
		 * @param  {[type]} $location_for_tags [description]
		 * @param  {[type]} $scope_property    [description]
		 * @return {[type]}                    [description]
		 */
		$scope.filterTags = function ($keycode, $typing, $location_for_tags, $scope_property) {
			if ($keycode !== 38 && $keycode !== 40 && $keycode !== 13) {
				//not up arrow, down arrow or enter, so filter tags
				autocomplete.removeSelected($scope.tags);
				$scope[$scope_property]['dropdown'] = true;
				$scope.autocomplete.tags = autocomplete.filterTags($scope.tags, $typing);
				if ($typing !== "" && $scope.autocomplete.tags.length > 0) {
					$scope.selected = autocomplete.selectFirstItem($scope.autocomplete.tags);
				}
			}
			else if ($keycode === 38) {
				//up arrow
				$scope.selected = autocomplete.upArrow($scope.autocomplete.tags);
			}
			else if ($keycode === 40) {
				//down arrow
				$scope.selected = autocomplete.downArrow($scope.autocomplete.tags);
			}
			else if ($keycode === 13) {				
				if ($location_for_tags === $scope.new_fixed_budget.tag) {
					//We are just autocompleting the budget tag input, not adding a tag anywhere
					if (!$typing) {
						return;
					}
					$scope.autocompleteFixedBudget();
					return;
				}
				else if ($location_for_tags === $scope.new_flex_budget.tag) {
					//We are just autocompleting the budget tag input, not adding a tag anywhere
					if (!$typing) {
						return;
					}
					$scope.autocompleteFlexBudget();
					return;
				}

				//resetting the dropdown to show all the tags again after a tag has been added
				$scope.autocomplete.tags = $scope.tags;
			}
		};
		
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
			budgets.updateBudget($scope.new_fixed_budget.tag.id, 'fixed_budget', $scope.new_fixed_budget.budget).then(function (response) {
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
			budgets.updateBudget($scope.new_flex_budget.tag.id, 'flex_budget', $scope.new_flex_budget.budget).then(function (response) {
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
				budgets.updateBudget($tag_id, 'fixed_budget', 'NULL').then(function (response) {
					$scope.getTotals();
				});
			}
		};

		$scope.removeFlexBudget = function ($tag_id, $tag_name) {
			if (confirm("remove flex budget for " + $tag_name + "?")) {
				budgets.updateBudget($tag_id, 'flex_budget', 'NULL').then(function (response) {
					$scope.getTotals();
				});
			}
		};

		$scope.updateCSDSetup = function ($tag) {
			$scope.edit_CSD = $tag;
			$scope.show.edit_CSD = true;
		};

		$scope.updateCSD = function () {
			budgets.updateCSD($scope.edit_CSD.id, $scope.edit_CSD.CSD).then(function (response) {
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

		/**
		 * delete
		 */
		
		/**
		 * totals-duplicates from main controller
		 */
		
		$scope.getTotals = function () {
			totals.basicTotals().then(function (response) {
				$scope.totals.basic = response.data;
			});
			totals.budget().then(function (response) {
				$scope.totals.budget = response.data;
			});
		};

		$scope.updateSavingsTotal = function ($keycode) {
			if ($keycode !== 13) {
				return;
			}
			savings.updatesavingsTotal().then(function (response) {
				$scope.totals.basic.savings_total = response.data;
				$scope.show.savings_total.input = false;
				$scope.show.savings_total.edit_btn = true;
				$scope.getTotals();
			});
		};

		$scope.addFixedToSavings = function ($keycode) {
			if ($keycode !== 13) {
				return;
			}
			savings.addFixedToSavings().then(function (response) {
				$scope.totals.basic.savings_total = response.data;
				$scope.getTotals();
			});
		};

		$scope.addPercentageToSavingsAutomatically = function ($amount_to_add) {
			savings.addPercentageToSavingsAutomatically($amount_to_add).then(function (response) {
				$scope.totals.basic.savings_total = response.data;
				$scope.getTotals();
			});
		};

		$scope.reverseAutomaticInsertIntoSavings = function ($amount_to_subtract) {
			savings.reverseAutomaticInsertIntoSavings($amount_to_subtract).then(function (response) {
				$scope.totals.basic.savings_total = response.data;
				$scope.getTotals();
			});
		};

		$scope.addPercentageToSavings = function ($keycode) {
			if ($keycode !== 13) {
				return;
			}
			savings.addPercentageToSavings().then(function (response) {
				$scope.totals.basic.savings_total = response.data;
				$scope.getTotals();
			});
		};

		/**
		 * page load
		 */
		
		$scope.getTotals();
		$scope.getTags();
		
	}); //end controller

})();