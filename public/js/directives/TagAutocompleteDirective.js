;(function(){
    'use strict';
    angular
        .module('budgetApp')
        .directive('tagAutocompleteDirective', tagAutocomplete);

    /* @inject */
    function tagAutocomplete(autocomplete) {
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

                $scope.duplicateTagCheck = function ($tag_id, $tag_array) {
                    //checks for duplicate tags when adding a new tag to an array
                    for (var i = 0; i < $tag_array.length; i++) {
                        if ($tag_array[i].tag_id === $tag_id) {
                            return false; //it is a duplicate
                        }
                    }
                    return true; //it is not a duplicate
                };


                $scope.addTag = function () {
                    $scope.messages.tag_exists = false;
                    //var $tag_id = $scope.selected.id;
                    var $tag_id = $scope.results[$scope.currentIndex].id;

                    if ($scope.duplicateTagCheck($tag_id, $scope.chosenTags)) {
                        $scope.chosenTags.push($scope.results[$scope.currentIndex]);
                        $scope.currentIndex = 0;
                    }
                    else {
                        $scope.messages.tag_exists = true;
                    }

                    //clearing the tag input
                    $scope.typing = '';
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
                    }
                };

                $scope.removeTag = function ($tag) {
                    $scope.chosenTags = _.without($scope.chosenTags, $tag);
                };
            }
        };
    }
}).call(this);

