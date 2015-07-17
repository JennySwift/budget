var app = angular.module('budgetApp');

(function () {

	// ===========================display controller===========================

	app.controller('settings', function ($scope, $http, settings) {

		/**
		 * scope properties
		 */

        $scope.me = me;
		$scope.autocomplete = {};
		$scope.edit_tag = false;
		$scope.edit_account = false;
		$scope.show = {
			popups: {}
		};
		$scope.edit_tag_popup = {};
		$scope.edit_account_popup = {};

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
		
		$scope.showEditAccountPopup = function ($account_id, $account_name) {
			$scope.edit_account_popup.id = $account_id;
			$scope.edit_account_popup.name = $account_name;
			$scope.show.popups.edit_account = true;
		};

		$scope.updateAccount = function () {
			settings.updateAccountName($scope.edit_account_popup.id, $scope.edit_account_popup.name).then(function (response) {
				$scope.getAccounts();
				$scope.show.popups.edit_account = false;
			});
		};

		$scope.showEditTagPopup = function ($tag_id, $tag_name) {
			$scope.edit_tag_popup.id = $tag_id;
			$scope.edit_tag_popup.name = $tag_name;
			$scope.show.popups.edit_tag = true;
		};

		$scope.updateTag = function () {
			settings.updateTagName($scope.edit_tag_popup.id, $scope.edit_tag_popup.name).then(function (response) {
				$scope.getTags();
				$scope.show.popups.edit_tag = false;
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