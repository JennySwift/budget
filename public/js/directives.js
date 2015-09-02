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


////File not being used
//;(function(){
//    'use strict';
//    angular
//        .module('budgetApp')
//        .directive('filterDirective', filter);
//
//    /* @inject */
//    function filter() {
//        return {
//            restrict: 'EA',
//            scope: {
//                "showFilter": "=show",
//                "accounts": "=accounts",
//                "filterTransactions": "&search"
//            },
//            templateUrl: 'filter',
//            //scope: true,
//            link: function($scope, elem, attrs) {
//                $scope.resetFilter = function () {
//                    $scope.filter = {
//                        budget: "all",
//                        total: "",
//                        types: [],
//                        accounts: [],
//                        single_date: "",
//                        from_date: "",
//                        to_date: "",
//                        description: "",
//                        merchant: "",
//                        tags: [],
//                        reconciled: "any",
//                        offset: 0,
//                        num_to_fetch: 20
//                    };
//                };
//
//                $scope.resetFilter();
//
//
//
//                $scope.$watchCollection('filter.accounts', function (newValue, oldValue) {
//                    if (newValue === oldValue) {
//                        return;
//                    }
//                    $scope.filterTransactions(true);
//                });
//            }
//        };
//    }
//}).call(this);
//

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

                        var $html = $sce.trustAsHtml($name.replace($substr, '<li class="label label-default ' + $response[i].type + '"><span class="highlight">' + $substr + '</span></li>'));
                        $response[i].html = $html;
                    }
                    console.log(2);
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
                "basicTotals": "=basictotals",
                "fixedBudgetTotals": "=fixedbudgettotals",
                "flexBudgetTotals": "=flexbudgettotals",
                "remainingBalance": "=remainingbalance",
                "provideFeedback" : "&providefeedback",
                "show": "=show"
            },
            templateUrl: 'totals-directive',
            //scope: true,
            link: function($scope, elem, attrs) {
                $scope.filterFactory = FilterFactory;
                //$scope.show = {
                //    basic_totals: true,
                //    budget_totals: true
                //};

                $scope.totalChanges = {
                    //RB: [],
                    //RBWEFLB: []
                };

                $scope.clearChanges = function () {
                    $scope.totalChanges = {
                        //RB: [],
                        //RBWEFLB: []
                    };
                };

                //$scope.$watch('filterFactory.totals', function (newValue, oldValue, scope) {
                //    if (newValue) {
                //        scope.totals.basic = newValue.basic;
                //        scope.totals.budget = newValue.budget;
                //    }
                //});

                $scope.$watch('filterFactory.basicTotals', function (newValue, oldValue, scope) {

                    if (newValue) {

                        if (newValue.credit !== $scope.basicTotals.credit) {
                            $scope.totalChanges.credit = $scope.format(newValue.credit, $scope.basicTotals.credit);
                        }

                        if (newValue.debit !== $scope.basicTotals.debit) {
                            $scope.totalChanges.debit = $scope.format(newValue.debit, $scope.basicTotals.debit);
                        }

                        if (newValue.balance !== $scope.basicTotals.balance) {
                            $scope.totalChanges.balance = $scope.format(newValue.balance, $scope.basicTotals.balance);
                        }

                        if (newValue.reconciledSum !== $scope.basicTotals.reconciledSum) {
                            $scope.totalChanges.reconciled = $scope.format(newValue.reconciledSum, $scope.basicTotals.reconciledSum);
                        }

                        if (newValue.savings !== $scope.basicTotals.savings) {
                            $scope.totalChanges.savings = $scope.format(newValue.savings, $scope.basicTotals.savings);
                        }

                        if (newValue.EWB !== $scope.basicTotals.EWB) {
                            $scope.totalChanges.EWB = $scope.format(newValue.EWB, $scope.basicTotals.EWB);
                        }

                        scope.basicTotals = newValue;
                    }
                });

                $scope.$watch('filterFactory.fixedBudgetTotals', function (newValue, oldValue, scope) {
                    if (newValue) {

                        if (newValue.remaining !== $scope.fixedBudgetTotals.remaining) {
                            $scope.totalChanges.remainingFixedBudget = $scope.format(newValue.remaining, $scope.fixedBudgetTotals.remaining);
                        }

                        if (newValue.cumulative !== $scope.fixedBudgetTotals.cumulative) {
                            $scope.totalChanges.cumulativeFixedBudget = $scope.format(newValue.cumulative, $scope.fixedBudgetTotals.cumulative);
                        }

                        if (newValue.spentBeforeStartingDate !== $scope.fixedBudgetTotals.spentBeforeStartingDate) {
                            $scope.totalChanges.fixedBudgetExpensesBeforeStartingDate = $scope.format(newValue.spentBeforeStartingDate, $scope.fixedBudgetTotals.spentBeforeStartingDate);
                        }

                        if (newValue.spentAfterStartingDate !== $scope.fixedBudgetTotals.spentAfterStartingDate) {
                            $scope.totalChanges.fixedBudgetExpensesAfterStartingDate = $scope.format(newValue.spentAfterStartingDate, $scope.fixedBudgetTotals.spentAfterStartingDate);
                        }

                        scope.fixedBudgetTotals = newValue;
                    }
                });

                $scope.$watch('filterFactory.flexBudgetTotals', function (newValue, oldValue, scope) {
                    if (newValue) {

                        if (newValue.spentBeforeStartingDate !== $scope.flexBudgetTotals.spentBeforeStartingDate) {
                            $scope.totalChanges.flexBudgetExpensesBeforeStartingDate = $scope.format(newValue.spentBeforeStartingDate, $scope.flexBudgetTotals.spentBeforeStartingDate);
                        }

                        if (newValue.spentAfterStartingDate !== $scope.flexBudgetTotals.spentAfterStartingDate) {
                            $scope.totalChanges.flexBudgetExpensesAfterStartingDate = $scope.format(newValue.spentAfterStartingDate, $scope.flexBudgetTotals.spentAfterStartingDate);
                        }

                        scope.flexBudgetTotals = newValue;
                    }
                });

                $scope.$watch('filterFactory.remainingBalance', function (newValue, oldValue, scope) {
                    if (newValue) {

                        if (newValue !== $scope.remainingBalance) {
                            $scope.totalChanges.remainingBalance = $scope.format(newValue, $scope.remainingBalance);
                        }

                        scope.remainingBalance = newValue;
                    }
                });

                /**
                 * End watches
                 */

                /**
                 * For formatting the numbers in the total changes to two decimal places
                 * @param newValue
                 * @param oldValue
                 * @returns {string}
                 */
                $scope.format = function (newValue, oldValue) {
                    //var $diff = newValue.replace(',', '') - oldValue.replace(',', '');
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