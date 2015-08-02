var app = angular.module('budgetApp');

(function () {

    app.controller('TagsController', function ($scope, $http, TagsFactory, FeedbackFactory) {

        /**
         * scope properties
         */

        $scope.me = me;
        $scope.autocomplete = {};
        $scope.edit_tag = false;
        $scope.feedback_messages = [];
        $scope.feedbackFactory = FeedbackFactory;
        $scope.edit_tag_popup = {};

        $scope.show = {
            popups: {}
        };

        $scope.$watch('feedbackFactory.data', function (newValue, oldValue, scope) {
            if (newValue && newValue.message) {
                scope.provideFeedback(newValue.message);
            }
        });

        $scope.provideFeedback = function ($message) {
            $scope.feedback_messages.push($message);
            setTimeout(function () {
                $scope.feedback_messages = _.without($scope.feedback_messages, $message);
                $scope.$apply();
            }, 3000);
        };

        $scope.responseError = function (response) {
            if (response.status === 503) {
                FeedbackFactory.provideFeedback('Sorry, application under construction. Please try again later.');
            }
            else {
                FeedbackFactory.provideFeedback('There was an error');
            }
        };

        /**
         * select
         */

        $scope.getTags = function () {
            TagsFactory.getTags()
                .then(function (response) {
                    $scope.tags = response.data;
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

            TagsFactory.duplicateTagCheck()
                .then(function (response) {
                    var $duplicate = response.data;
                    if ($duplicate > 0) {
                        FeedbackFactory.provideFeedback('You already have a tag with that name');
                    }
                    else {
                        TagsFactory.insertTag()
                            .then(function (response) {
                                $scope.getTags();
                                $("#new-tag-input").val("");
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
            TagsFactory.updateTagName($scope.edit_tag_popup.id, $scope.edit_tag_popup.name)
                .then(function (response) {
                    $scope.getTags();
                    $scope.show.popups.edit_tag = false;
                })
                .catch(function (response) {
                    $scope.responseError(response);
                })
        };

        /**
         * delete
         */

        $scope.deleteTag = function ($tag_id) {
            TagsFactory.countTransactionsWithTag($tag_id)
                .then(function (response) {
                    var $count = response.data;
                    if (confirm("You have " + $count + " transactions with this tag. Are you sure?")) {
                        TagsFactory.deleteTag($tag_id)
                            .then(function (response) {
                                $scope.getTags();
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