var app = angular.module('budgetApp');

(function () {

    app.controller('TagsController', function ($scope, $http, TagsFactory, FeedbackFactory) {

        /**
         * scope properties
         */

        $scope.autocomplete = {};
        $scope.edit_tag = false;
        $scope.feedbackFactory = FeedbackFactory;
        $scope.edit_tag_popup = {};

        $scope.$watch('feedbackFactory.data', function (newValue, oldValue, scope) {
            if (newValue && newValue.message) {
                scope.provideFeedback(newValue.message);
            }
        });

        /**
         * select
         */

        $scope.getTags = function () {
            $scope.showLoading();
            TagsFactory.getTags()
                .then(function (response) {
                    $scope.tags = response.data;
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                })
        };
        $scope.getTags();

        /**
         * insert
         */

        /**
         * Inserts a new tag into tags table, not into a transaction
         * @param $keycode
         */
        $scope.insertTag = function ($keycode) {
            if ($keycode !== 13) {
                return;
            }

            $scope.showLoading();
            TagsFactory.duplicateTagCheck()
                .then(function (response) {
                    var $duplicate = response.data;
                    if ($duplicate > 0) {
                        FeedbackFactory.provideFeedback('You already have a tag with that name');
                        $scope.hideLoading();
                    }
                    else {
                        $scope.showLoading();
                        TagsFactory.insertTag()
                            .then(function (response) {
                                $scope.getTags();
                                $("#new-tag-input").val("");
                                $scope.hideLoading();
                                $scope.provideFeedback('Tag created');
                            })
                            .catch(function (response) {
                                $scope.responseError(response);
                            })
                    }
                })
                .catch(function (response) {
                    $scope.responseError(response);
                })
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
            $scope.showLoading();
            TagsFactory.updateTagName($scope.edit_tag_popup.id, $scope.edit_tag_popup.name)
                .then(function (response) {
                    $scope.getTags();
                    $scope.show.popups.edit_tag = false;
                    $scope.hideLoading();
                    $scope.provideFeedback('Tag edited');
                })
                .catch(function (response) {
                    $scope.responseError(response);
                })
        };

        /**
         * delete
         */

        $scope.deleteTag = function ($tag_id) {
            $scope.showLoading();
            TagsFactory.countTransactionsWithTag($tag_id)
                .then(function (response) {
                    var $count = response.data;
                    //The loading symbol isn't hiding here because of the confirm()
                    $scope.hideLoading();
                    if (confirm("You have " + $count + " transactions with this tag. Are you sure?")) {
                        $scope.showLoading();
                        TagsFactory.deleteTag($tag_id)
                            .then(function (response) {
                                $scope.getTags();
                                $scope.hideLoading();
                                $scope.provideFeedback('Tag deleted');
                            })
                            .catch(function (response) {
                                $scope.responseError(response);
                            })
                    }
                })
                .catch(function (response) {
                    $scope.responseError(response);
                })
        };

    }); //end controller

})();