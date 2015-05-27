var app = angular.module('budgetApp');

(function () {

	// ===========================display controller===========================

	app.controller('settings', function ($scope, $http, settings) {

		/**
		 * scope properties
		 */
		
		$scope.autocomplete = {};
		$scope.edit_tag = false;
		$scope.edit_account = false;
		$scope.show = {
			popups: {}
		};

		/**
		 * select
		 */
		
		$scope.getAccounts = function () {
			settings.getAccounts().then(function (response) {
				$scope.accounts = response.data;
			});
		};

		$scope.getTags = function () {
			settings.getTags().then(function (response) {
				$scope.tags = response.data;
				$scope.autocomplete.tags = response.data;
			});
		};
		$scope.getTags();

		/**
		 * insert
		 */
		
		$scope.insertAccount = function ($keycode) {
			if ($keycode !== 13) {
				return;
			}

			settings.insertAccount().then(function (response) {
				$scope.getAccounts();
				$("#new_account_input").val("");
			});
		};

		$scope.insertTag = function ($keycode) {
			if ($keycode !== 13) {
				return;
			}

			//inserts a new tag into tags table, not into a transaction
			settings.duplicateTagCheck().then(function (response) {
				var $duplicate = response.data;
				if ($duplicate > 0) {
					$("#tag-already-created").show();
				}
				else {
					settings.insertTag().then(function (response) {
						$scope.getTags();
						$("#new-tag-input").val("");
					});
				}
			});
		};

		/**
		 * update
		 */
		
		$scope.updateAccountSetup = function ($account_id, $account_name) {
			$scope.edit_account.id = $account_id;
			$scope.edit_account.name = $account_name;
			$scope.show.popups.edit_account = true;
		};

		$scope.updateAccount = function () {
			settings.updateAccountName($scope.edit_account.id, $scope.edit_account.name).then(function (response) {
				$scope.getAccounts();
				$scope.show.popups.edit_account = false;
			});
		};

		$scope.updateTagSetup = function ($tag_id, $tag_name) {
			$scope.edit_tag.id = $tag_id;
			$scope.edit_tag.name = $tag_name;
			$scope.show.popups.edit_tag = true;
		};

		$scope.updateTag = function () {
			settings.updateTagName($scope.edit_tag.id, $scope.edit_tag.name).then(function (response) {
				$scope.getTags();
				$scope.show.popups.edit_tag = false;
			});
		};

		$scope.editTagName = function () {
			settings.editTagName().then(function (response) {
				$(".appended_tag_div li").each(function () {
					if ($(this).text() === $old_name) {
						$(this).text($new_name);
						tagString();
						saveEdit($(this).closest(".tag_container").siblings(".results_inner_div"));
					}
				});
			});
		};

		$scope.updateColors = function () {
			settings.updateColors($scope.colors).then(function (response) {
				$scope.getColors();
				$scope.show.color_picker = false;
			});
		};

		/**
		 * delete
		 */
		
		$scope.deleteTag = function ($tag_id) {
			settings.countTransactionsWithTag($tag_id).then(function (response) {
				var $count = response.data;
				if (confirm("You have " + $count + " transactions with this tag. Are you sure?")) {
					settings.deleteTag($tag_id).then(function (response) {
						$scope.getTags();
					});
				}
			});
		};

		$scope.deleteAccount = function ($account_id) {
			if (confirm("Are you sure you want to delete this account?")) {
				settings.deleteAccount($account_id).then(function (response) {
					$scope.getAccounts();
				});
			}
		};

		/**
		 * other
		 */
		
		$scope.closePopup = function ($event, $popup) {
			var $target = $event.target;
			if ($target.className === 'popup-outer') {
				$scope.show.popups[$popup] = false;
			}
		};

		/**
		 * page load
		 */
		
		$scope.getAccounts();

		
	}); //end controller

})();