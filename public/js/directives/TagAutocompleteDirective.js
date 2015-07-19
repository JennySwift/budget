;(function(){
    'use strict';
    angular
        .module('budgetApp')
        .directive('tagAutocompleteDirective', tagAutocomplete);

    /* @inject */
    function tagAutocomplete(autocomplete, FeedbackFactory, $sce) {
        return {
            restrict: 'EA',
            scope: {
                "chosenTags": "=chosentags",
                "dropdown": "=dropdown",
                "tags": "=tags"
            },
            templateUrl: 'templates/TagAutocompleteTemplate.php',
            //scope: true,
            link: function($scope, elem, attrs) {
                //$scope.currentIndex = 1;
                $scope.results = {};
                $scope.messages = {};

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


                $scope.addTag = function ($index) {
                    if ($index !== undefined) {
                        //Item was chosen by clicking, not by pressing enter
                        $scope.currentIndex = $index;
                    }

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
                    $scope.typing = '';
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
                $scope.filterTags = function ($keycode, $typing) {
                    if ($keycode === 13) {
                        //enter is pressed
                        //$scope.chooseItem();

                        if ($scope.results[$scope.currentIndex].length === 0) {
                            //We are not adding a tag. We are inserting the transaction.
                            $scope.insertTransaction(13);
                            return;
                        }
                        //We are adding a tag
                        $scope.addTag();

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
                        $scope.dropdown = true;
                        $scope.results = autocomplete.filterTags($scope.tags, $typing);
                        $scope.results = $scope.highlightLetters($scope.results, $typing);
                    }
                };

                $scope.removeTag = function ($tag) {
                    $scope.chosenTags = _.without($scope.chosenTags, $tag);
                };
            }
        };
    }
}).call(this);

