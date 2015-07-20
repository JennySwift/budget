;(function(){
    'use strict';
    angular
        .module('budgetApp')
        .directive('autocompleteDirective', autocomplete);

    /* @inject */
    function autocomplete(FeedbackFactory, autocomplete, $sce, $http) {
        return {
            restrict: 'EA',
            scope: {
                "dropdown": "=dropdown",
                "placeholder": "@placeholder",
                "typing": "=typing",
                "new_transaction": "=newtransaction",
                "fnOnEnter": "&fnonenter"
            },
            templateUrl: 'templates/AutocompleteTemplate.php',
            link: function($scope, elem, attrs) {
                $scope.results = {};
                $scope.typing = '';

                /**
                 * Hide the dropdown and clear the input field
                 */
                $scope.hideAndClear = function () {
                    $scope.hideDropdown();
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
                $scope.filter = function ($keycode) {
                    if ($keycode === 13) {
                        //enter is pressed
                        if (!$scope.results[$scope.currentIndex]) {
                            //We are not adding a tag. We are inserting the transaction.
                            $scope.fnOnEnter();
                            return;
                        }
                        //We are adding a tag
                        $scope.chooseItem();

                        //resetting the dropdown to show all the tags again after a tag has been added
                        //$scope.results = $scope.tags;
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
                    $scope.results = $scope.highlightLetters($scope.searchDatabase(), $scope.typing);
                };

                $scope.searchLocal = function () {
                    var $results = _.filter($scope.tags, function ($tag) {
                        return $tag.name.toLowerCase().indexOf($scope.typing.toLowerCase()) !== -1;
                    });

                    return $results;
                };

                /**
                 * Query the database
                 */
                $scope.searchDatabase = function () {
                    var $data = {
                        typing: $scope.typing,
                        column: $scope.placeholder
                    };

                    return $http.post('select/autocompleteTransaction', $data).
                        success(function(response, status, headers, config) {
                            //$scope.dealWithResults(response);
                            $scope.results = response;
                            $scope.results = autocomplete.transferTransactions($scope.results);
                            $scope.results = autocomplete.removeDuplicates($scope.results);
                        }).
                        error(function(data, status, headers, config) {
                            console.log("There was an error");
                        });
                };

                $scope.chooseItem = function ($index) {
                    if ($index !== undefined) {
                        //Item was chosen by clicking, not by pressing enter
                        $scope.currentIndex = $index;
                    }

                    $scope.selectedItem = $scope.results[$scope.currentIndex];

                    $scope.fillFields();

                    $scope.hideAndClear();
                };

                $scope.fillFields = function () {
                    if ($scope.placeholder === 'description') {
                        $scope.typing = $scope.selectedItem.description;
                        $scope.new_transaction.merchant = $scope.selectedItem.merchant;
                    }
                    else if ($scope.placeholder === 'merchant') {
                        $scope.typing = $scope.selectedItem.merchant;
                        $scope.new_transaction.description = $scope.selectedItem.description;
                    }

                    $scope.new_transaction.total = $scope.selectedItem.total;
                    $scope.new_transaction.type = $scope.selectedItem.type;
                    $scope.new_transaction.account = $scope.selectedItem.account.id;

                    if ($scope.selectedItem.from_account && $scope.selectedItem.to_account) {
                        $scope.new_transaction.from_account = $scope.selectedItem.from_account.id;
                        $scope.new_transaction.to_account = $scope.selectedItem.to_account.id;
                    }

                    $scope.new_transaction.tags = $scope.selectedItem.tags;
                };

            }
        };
    }
}).call(this);

