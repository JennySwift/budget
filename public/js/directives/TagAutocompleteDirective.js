;(function(){
    'use strict';
    angular
        .module('budgetApp')
        .directive('tagAutocompleteDirective', tagAutocomplete);

    /* @inject */
    function tagAutocomplete(FeedbackFactory, $sce) {
        return {
            restrict: 'EA',
            scope: {
                "chosenTags": "=chosentags",
                "dropdown": "=dropdown",
                "tags": "=tags",
                "fnOnEnter": "&fnonenter",
                "multipleTags": "=multipletags"
            },
            templateUrl: 'templates/TagAutocompleteTemplate.php',
            //scope: true,
            link: function($scope, elem, attrs) {
                //$scope.currentIndex = 1;
                $scope.results = {};
                $scope.messages = {};
                $scope.typing = '';

                /**
                 * Check for duplicate tags when adding a new tag to an array
                 * @param $tag_id
                 * @param $tag_array
                 * @returns {boolean}
                 */
                $scope.duplicateTagCheck = function ($tag_id, $tag_array) {
                    for (var i = 0; i < $tag_array.length; i++) {
                        if ($tag_array[i].id === $tag_id) {
                            return false; //it is a duplicate
                        }
                    }
                    return true; //it is not a duplicate
                };


                $scope.chooseTag = function ($index) {
                    if ($index !== undefined) {
                        //Item was chosen by clicking, not by pressing enter
                        $scope.currentIndex = $index;
                    }

                    if ($scope.multipleTags) {
                        $scope.addTag();
                    }
                    else {
                        $scope.fillField();
                    }
                };

                /**
                 * For if only one tag can be chosen
                 */
                $scope.fillField = function () {
                    $scope.typing = $scope.results[$scope.currentIndex].name;
                    $scope.hideAndClear();
                };

                /**
                 * For if multiple tags can be chosen
                 */
                $scope.addTag = function () {
                    var $tag_id = $scope.results[$scope.currentIndex].id;

                    if (!$scope.duplicateTagCheck($tag_id, $scope.chosenTags)) {
                        FeedbackFactory.provideFeedback('You have already entered that tag');
                        $scope.hideAndClear();
                        return;
                    }

                    $scope.chosenTags.push($scope.results[$scope.currentIndex]);
                    $scope.hideAndClear();
                };

                /**
                 * Hide the dropdown and clear the input field
                 */
                $scope.hideAndClear = function () {
                    $scope.hideDropdown();

                    if ($scope.multipleTags) {
                        $scope.typing = '';
                    }

                    $scope.currentIndex = null;
                    $('.highlight').removeClass('highlight');
                };

                $scope.hideDropdown = function () {
                    $scope.dropdown = false;
                };

                $scope.highlightLetters = function ($response, $typing) {
                    $typing = $typing.toLowerCase();

                    for (var i = 0; i < $response.length; i++) {
                        var $name = $response[i].name;
                        var $index = $name.toLowerCase().indexOf($typing);
                        var $substr = $name.substr($index, $typing.length);
                        var $html = $sce.trustAsHtml($name.replace($substr, '<span class="highlight">' + $substr + '</span>'));
                        $response[i].html = $html;
                    }
                    return $response;
                };

                $scope.hoverItem = function(index) {
                    $scope.currentIndex = index;
                };

                /**
                 * Act on keypress for input field
                 * @param $keycode
                 * @returns {boolean}
                 */
                $scope.filterTags = function ($keycode) {
                    if ($keycode === 13) {
                        //enter is pressed
                        //$scope.chooseItem();

                        if (!$scope.results[$scope.currentIndex]) {
                            //We are not adding a tag. We are inserting the transaction.
                            $scope.fnOnEnter();
                            return;
                        }
                        //We are choosing a tag
                        $scope.chooseTag();

                        //resetting the dropdown to show all the tags again after a tag has been added
                        $scope.results = $scope.tags;
                    }
                    else if ($keycode === 38) {
                        //up arrow is pressed
                        if ($scope.currentIndex > 0) {
                            $scope.currentIndex--;
                        }
                    }
                    else if ($keycode === 40) {
                        //down arrow is pressed
                        if ($scope.currentIndex + 1 < $scope.results.length) {
                            $scope.currentIndex++;
                        }
                    }
                    else {
                        //Not enter, up or down arrow
                        $scope.currentIndex = 0;
                        $scope.showDropdown();
                    }
                };

                $scope.showDropdown = function () {
                    $scope.dropdown = true;
                    $scope.results = $scope.highlightLetters($scope.searchLocal(), $scope.typing);
                };

                $scope.searchLocal = function () {
                    var $filtered_tags = _.filter($scope.tags, function ($tag) {
                        return $tag.name.toLowerCase().indexOf($scope.typing.toLowerCase()) !== -1;
                    });

                    return $filtered_tags;
                };

                $scope.removeTag = function ($tag) {
                    $scope.chosenTags = _.without($scope.chosenTags, $tag);
                };
            }
        };
    }
}).call(this);

