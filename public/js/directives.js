;(function(){
    'use strict';
    angular
        .module('budgetApp')
        .directive('checkbox', checkbox);

    /* @inject */
    function checkbox() {
        return {
            restrict: 'EA',
            scope: {
                "model": "=model",
                "id": "@id"
            },
            templateUrl: 'checkboxes',
            link: function($scope, elem, attrs) {
                $scope.animateIn = attrs.animateIn || 'zoomIn';
                $scope.animateOut = attrs.animateOut || 'zoomOut';
                $scope.icon = $(elem).find('.label-icon');

                $scope.toggleIcon = function () {
                    if (!$scope.model) {
                        //Input was checked and now it won't be
                        $scope.hideIcon();
                    }
                    else {
                        //Input was not checked and now it will be
                        $scope.showIcon();
                    }
                };

                $scope.hideIcon = function () {
                    $($scope.icon).removeClass($scope.animateIn)
                        .addClass($scope.animateOut);
                };

                $scope.showIcon = function () {
                    $($scope.icon).css('display', 'flex')
                        .removeClass($scope.animateOut)
                        .addClass($scope.animateIn);
                };

                //Make the checkbox checked on page load if it should be
                if ($scope.model === true) {
                    $scope.showIcon();
                }

                $scope.$watch('model', function (newValue, oldValue) {
                    $scope.toggleIcon();
                });
            }
        };
    }
}).call(this);


;(function(){
    'use strict';
    angular
        .module('budgetApp')
        .directive('dropdownsDirective', dropdown);

    /* @inject */
    function dropdown($parse, $http) {
        return {
            restrict: 'EA',
            //scope: {
            //    //"id": "@id",
            //    //"selectedObject": "=selectedobject",
            //    'url': '@url',
            //    'showPopup': '=show'
            //},
            //templateUrl: 'templates/DropdownsTemplate.php',
            scope: true,
            link: function($scope, elem, attrs) {
                $scope.animateIn = attrs.animateIn || 'flipInX';
                $scope.animateOut = attrs.animateOut || 'flipOutX';
                var $content = $(elem).find('.dropdown-content');

                $scope.toggleDropdown = function () {
                    if ($($content).hasClass($scope.animateIn)) {
                        $scope.hideDropdown();
                    }
                    else {
                        $scope.showDropdown();
                    }
                };

                //Todo: Why is this click firing twice?
                $("body").on('click', function (event) {
                    if (!elem[0].contains(event.target)) {
                        $scope.hideDropdown();
                    }
                });

                $scope.showDropdown = function () {
                    $($content)
                        .css('display', 'flex')
                        .removeClass($scope.animateOut)
                        .addClass($scope.animateIn);
                };

                $scope.hideDropdown = function () {
                    $($content)
                        .removeClass($scope.animateIn)
                        .addClass($scope.animateOut);
                        //.css('display', 'none');
                };
            }
        };
    }
}).call(this);


;(function(){
    'use strict';
    angular
        .module('budgetApp')
        .directive('filterDropdownsDirective', filterDropdown);

    /* @inject */
    function filterDropdown($parse, $http) {
        return {
            restrict: 'A',
            //scope: {
            //    //"model": "=model",
            //    //"id": "@id"
            //    "types": "=types",
            //    "path": "@path"
            //},
            //templateUrl: 'filter-dropdowns',
            scope: true,
            link: function($scope, elem, attrs) {
                $scope.content = $(elem).find('.content');
                var $h4 = $(elem).find('h4');

                $($h4).on('click', function () {
                    $scope.toggleContent();
                });

                $scope.toggleContent = function () {
                    if ($scope.contentVisible) {
                        $scope.hideContent();
                    }
                    else {
                        $scope.showContent();
                    }
                };

                $scope.showContent = function () {
                    $scope.content.slideDown();
                    $scope.contentVisible = true;
                };

                $scope.hideContent = function () {
                    $scope.content.slideUp();
                    $scope.contentVisible = false;
                };
            }
        };
    }
}).call(this);


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
                "multipleTags": "=multipletags",
                "model": "=model",
                //"typing": "=modelname",
                "id": "@id",
                "focusOnEnter": "@focusonenter"
            },
            templateUrl: 'tag-autocomplete',
            link: function($scope, elem, attrs) {
                $scope.results = {};
                $scope.messages = {};
                //$scope.dropdown = true;

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
                    $scope.model = $scope.results[$scope.currentIndex];
                    if ($scope.focusOnEnter) {
                        // Todo: This line doesn't work if tag is chosen with mouse click
                        $("#" + $scope.focusOnEnter).focus();
                    }
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

                /**
                 * Todo: when the new budget tag input is focused after entering a budget,
                 * todo: I don't want the dropdown to show. I had a lot of trouble and need help though.
                 */
                $scope.showDropdown = function () {
                    $scope.dropdown = true;
                    if ($scope.typing) {
                        $scope.results = $scope.highlightLetters($scope.searchLocal(), $scope.typing);
                    }
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


;(function(){
    'use strict';
    angular
        .module('budgetApp')
        .directive('totalsDirective', totals);

    /* @inject */
    function totals(SavingsFactory, FilterFactory) {
        return {
            restrict: 'EA',
            scope: {
                //"totals": "=totals",
                "sideBarTotals": "=sidebartotals",
                //"basicTotals": "=basictotals",
                //"fixedBudgetTotals": "=fixedbudgettotals",
                //"flexBudgetTotals": "=flexbudgettotals",
                //"remainingBalance": "=remainingbalance",
                "totalChanges": "=totalchanges",
                "provideFeedback" : "&providefeedback",
                "show": "=show"
            },
            //template: $('script#totals').html(),
            templateUrl: '/totals-directive',
            //scope: true,
            link: function($scope, elem, attrs) {

                $scope.$watch('sideBarTotals', function (newValue, oldValue, scope) {

                    if (newValue && oldValue) {

                        if (newValue.credit !== oldValue.credit) {
                            $scope.totalChanges.credit = $scope.calculateDifference(newValue.credit, oldValue.credit);
                        }

                        if (newValue.debit !== oldValue.debit) {
                            $scope.totalChanges.debit = $scope.calculateDifference(newValue.debit, oldValue.debit);
                        }

                        if (newValue.balance !== oldValue.balance) {
                            $scope.totalChanges.balance = $scope.calculateDifference(newValue.balance, oldValue.balance);
                        }

                        if (newValue.reconciledSum !== oldValue.reconciledSum) {
                            $scope.totalChanges.reconciledSum = $scope.calculateDifference(newValue.reconciledSum, oldValue.reconciledSum);
                        }

                        if (newValue.savings !== oldValue.savings) {
                            $scope.totalChanges.savings = $scope.calculateDifference(newValue.savings, oldValue.savings);
                        }

                        if (newValue.expensesWithoutBudget !== oldValue.expensesWithoutBudget) {
                            $scope.totalChanges.expensesWithoutBudget = $scope.calculateDifference(newValue.expensesWithoutBudget, oldValue.expensesWithoutBudget);
                        }

                        if (newValue.remainingFixedBudget !== oldValue.remainingFixedBudget) {
                            $scope.totalChanges.remainingFixedBudget = $scope.calculateDifference(newValue.remainingFixedBudget, oldValue.remainingFixedBudget);
                        }

                        if (newValue.cumulativeFixedBudget !== oldValue.cumulativeFixedBudget) {
                            $scope.totalChanges.cumulativeFixedBudget = $scope.calculateDifference(newValue.cumulativeFixedBudget, oldValue.cumulativeFixedBudget);
                        }

                        if (newValue.expensesWithFixedBudgetBeforeStartingDate !== oldValue.expensesWithFixedBudgetBeforeStartingDate) {
                            $scope.totalChanges.expensesWithFixedBudgetBeforeStartingDate = $scope.calculateDifference(newValue.expensesWithFixedBudgetBeforeStartingDate, oldValue.expensesWithFixedBudgetBeforeStartingDate);
                        }

                        if (newValue.expensesWithFixedBudgetAfterStartingDate !== oldValue.expensesWithFixedBudgetAfterStartingDate) {
                            $scope.totalChanges.expensesWithFixedBudgetAfterStartingDate = $scope.calculateDifference(newValue.expensesWithFixedBudgetAfterStartingDate, oldValue.expensesWithFixedBudgetAfterStartingDate);
                        }

                        if (newValue.expensesWithFlexBudgetBeforeStartingDate !== oldValue.expensesWithFlexBudgetBeforeStartingDate) {
                            $scope.totalChanges.expensesWithFlexBudgetBeforeStartingDate = $scope.calculateDifference(newValue.expensesWithFlexBudgetBeforeStartingDate, oldValue.expensesWithFlexBudgetBeforeStartingDate);
                        }

                        if (newValue.expensesWithFlexBudgetAfterStartingDate !== oldValue.expensesWithFlexBudgetAfterStartingDate) {
                            $scope.totalChanges.expensesWithFlexBudgetAfterStartingDate = $scope.calculateDifference(newValue.expensesWithFlexBudgetAfterStartingDate, oldValue.expensesWithFlexBudgetAfterStartingDate);
                        }

                        if (newValue.remainingBalance !== oldValue.remainingBalance) {
                            $scope.totalChanges.remainingBalance = $scope.calculateDifference(newValue.remainingBalance, oldValue.remainingBalance);
                        }

                        scope.sideBarTotals = newValue;
                    }
                });

                /**
                 * End watches
                 */

                /**
                 * @param newValue
                 * @param oldValue
                 * @returns {string}
                 */
                $scope.calculateDifference = function (newValue, oldValue) {
                    var $diff = newValue - oldValue;
                    return $diff.toFixed(2);
                };

                $scope.showSavingsTotalInput = function () {
                    $scope.show.savings_total.input = true;
                    $scope.show.savings_total.edit_btn = false;
                };
            }
        };
    }
}).call(this);


;(function(){
    'use strict';
    angular
        .module('budgetApp')
        .directive('transactionAutocompleteDirective', transactionAutocomplete);

    /* @inject */
    function transactionAutocomplete(FeedbackFactory, AutocompleteFactory, $sce, $http, $timeout, $interval) {
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
            templateUrl: 'transaction-autocomplete',
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

                    return $http.post('select/autocompleteTransaction', $data).
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


//# sourceMappingURL=directives.js.map