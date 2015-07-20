var app = angular.module('budgetApp');

(function () {

    // ===========================display controller===========================

    app.controller('TagsController', function ($scope, $http, TagsFactory) {

        /**
         * scope properties
         */

        $scope.me = me;
        $scope.autocomplete = {};
        $scope.edit_tag = false;

        $scope.show = {
            popups: {}
        };
        $scope.edit_tag_popup = {};

        /**
         * select
         */

        $scope.getTags = function () {
            TagsFactory.getTags().then(function (response) {
                $scope.tags = response.data;
            });
        };
        $scope.getTags();

        /**
         * insert
         */

        $scope.insertTag = function ($keycode) {
            if ($keycode !== 13) {
                return;
            }

            //inserts a new tag into tags table, not into a transaction
            TagsFactory.duplicateTagCheck().then(function (response) {
                var $duplicate = response.data;
                if ($duplicate > 0) {
                    $("#tag-already-created").show();
                }
                else {
                    TagsFactory.insertTag().then(function (response) {
                        $scope.getTags();
                        $("#new-tag-input").val("");
                    });
                }
            });
        };

        /**
         * update
         */

        $scope.showEditTagPopup = function ($tag_id, $tag_name) {
            $scope.edit_tag_popup.id = $tag_id;
            $scope.edit_tag_popup.name = $tag_name;
            $scope.show.popups.edit_tag = true;
        };

        $scope.updateTag = function () {
            TagsFactory.updateTagName($scope.edit_tag_popup.id, $scope.edit_tag_popup.name).then(function (response) {
                $scope.getTags();
                $scope.show.popups.edit_tag = false;
            });
        };

        /**
         * delete
         */

        $scope.deleteTag = function ($tag_id) {
            TagsFactory.countTransactionsWithTag($tag_id).then(function (response) {
                var $count = response.data;
                if (confirm("You have " + $count + " transactions with this tag. Are you sure?")) {
                    TagsFactory.deleteTag($tag_id).then(function (response) {
                        $scope.getTags();
                    });
                }
            });
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

    }); //end controller

})();