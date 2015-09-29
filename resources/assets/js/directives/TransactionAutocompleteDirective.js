;(function(){
    'use strict';
    angular
        .module('budgetApp')
        .directive('transactionAutocompleteDirective', transactionAutocomplete);

    /* @inject */
    function transactionAutocomplete(AutocompleteFactory, $sce, $http, $interval) {
        return {
            restrict: 'EA',
            scope: {
                "dropdown": "=dropdown",
                "placeholder": "@placeholder",
                "typing": "=typing",
                "new_transaction": "=newtransaction",
                "fnOnEnter": "&fnonenter",
                "showLoading": "&showloading",
                "hideLoading": "&hideloading",
                "loading": "=loading"
            },
            templateUrl: 'transaction-autocomplete-template',
            link: function($scope, elem, attrs) {
                $scope.results = {};

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
                        $scope.startCounting();
                        $scope.currentIndex = 0;
                        $scope.showDropdown();
                    }
                };

                $scope.startCounting = function () {
                    $interval.cancel($scope.interval);
                    $scope.timeSinceKeyPress = 0;
                    $scope.interval = $interval(function () {
                        $scope.timeSinceKeyPress++;
                        $scope.showDropdown();
                    }, 500);
                };

                $scope.showDropdown = function () {
                    $scope.dropdown = true;

                    if ($scope.timeSinceKeyPress > 1) {
                        $scope.results = $scope.highlightLetters($scope.searchDatabase(), $scope.typing);
                        $interval.cancel($scope.interval);
                    }
                };

                $scope.searchLocal = function () {
                    var $results = _.filter($scope.tags, function ($tag) {
                        return $tag.name.toLowerCase().indexOf($scope.typing.toLowerCase()) !== -1;
                    });

                    return $results;
                };

                //var $responseNum = 0;

                /**
                 * Query the database
                 */
                $scope.searchDatabase = function () {
                    $scope.showLoading();
                    var $data = {
                        typing: $scope.typing,
                        column: $scope.placeholder
                    };

                    return $http.post('/api/autocomplete/transaction', $data).
                        success(function(response, status, headers, config) {
                            $scope.results = AutocompleteFactory.transferTransactions(response);
                            $scope.results = AutocompleteFactory.removeDuplicates($scope.results);
                            $scope.hideLoading();
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
                    $scope.new_transaction.account_id = $scope.selectedItem.account.id;

                    if ($scope.selectedItem.from_account && $scope.selectedItem.to_account) {
                        $scope.new_transaction.from_account_id = $scope.selectedItem.from_account.id;
                        $scope.new_transaction.to_account_id = $scope.selectedItem.to_account.id;
                    }

                    $scope.new_transaction.budgets = $scope.selectedItem.budgets;
                };

            }
        };
    }
}).call(this);

