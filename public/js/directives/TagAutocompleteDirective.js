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
                //"totals": "=totals",
                //"provideFeedback" : "&providefeedback"
                "new_transaction": "=newtransaction",
                "tags": "=tags"
            },
            templateUrl: 'templates/TagAutocompleteTemplate.php',
            //scope: true,
            link: function($scope, elem, attrs) {
                $scope.autocomplete = {};
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


                $scope.addTagToTransaction = function ($tags) {
                    $scope.messages.tag_exists = false;
                    var $tag_id = $scope.selected.id;

                    if ($scope.duplicateTagCheck($tag_id, $tags)) {
                        $tags.push($scope.selected);

                        autocomplete.removeSelected($scope.autocomplete.tags);
                    }
                    else {
                        $scope.messages.tag_exists = true;
                    }

                    //clearing the tag input
                    $scope.typing = {};
                };

                /**
                 * Almost duplicate of filterTags in budgets controller
                 * @param $keycode
                 * @param $typing
                 * @param $location_for_tags
                 * @param $scope_property
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
                        var $selected = $("#new-transaction .selected");
                        if ($selected.length === 0 && $location_for_tags === $scope.new_transaction.tags) {
                            //We are not adding a tag. We are inserting the transaction.
                            $scope.insertTransaction(13);
                            return;
                        }
                        //We are adding a tag
                        $scope.addTagToTransaction($location_for_tags);

                        //resetting the dropdown to show all the tags again after a tag has been added
                        $scope.autocomplete.tags = $scope.tags;
                    }
                };

                $scope.removeTag = function ($tag, $array, $scope_property) {
                    $scope[$scope_property]['tags'] = _.without($array, $tag);
                };
            }
        };
    }
}).call(this);

