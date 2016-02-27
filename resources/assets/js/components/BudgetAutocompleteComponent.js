var BudgetAutocomplete = Vue.component('budget-autocomplete', {
    template: '#budget-autocomplete-template',
    data: function () {
        return {
            results: {},
            messages: {}
        };
    },
    components: {},
    methods: {
        /**
         * Check for duplicate tags when adding a new tag to an array
         * @param $tag_id
         * @param $tag_array
         * @returns {boolean}
         */
        duplicateTagCheck: function ($tag_id, $tag_array) {
            for (var i = 0; i < $tag_array.length; i++) {
                if ($tag_array[i].id === $tag_id) {
                    return false; //it is a duplicate
                }
            }
            return true; //it is not a duplicate
        },


        chooseTag: function ($index) {
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
        },

        /**
         * For if only one tag can be chosen
         */
        fillField: function () {
            $scope.typing = $scope.results[$scope.currentIndex].name;
            $scope.model = $scope.results[$scope.currentIndex];
            if ($scope.focusOnEnter) {
                // Todo: This line doesn't work if tag is chosen with mouse click
                $("#" + $scope.focusOnEnter).focus();
            }
            $scope.hideAndClear();
        },

        /**
         * For if multiple tags can be chosen
         */
        addTag: function () {
            var $tag_id = $scope.results[$scope.currentIndex].id;

            if (!$scope.duplicateTagCheck($tag_id, $scope.chosenTags)) {
                //$rootScope.$broadcast('provideFeedback', 'You have already entered that tag');
                $scope.hideAndClear();
                return;
            }

            $scope.chosenTags.push($scope.results[$scope.currentIndex]);
            $scope.hideAndClear();
        },

        /**
         * Hide the dropdown and clear the input field
         */
        hideAndClear: function () {
            $scope.hideDropdown();

            if ($scope.multipleTags) {
                $scope.typing = '';
            }

            $scope.currentIndex = null;
            $('.highlight').removeClass('highlight');
        },

        hideDropdown: function () {
            $scope.dropdown = false;
        },

        highlightLetters: function ($response, $typing) {
            $typing = $typing.toLowerCase();

            for (var i = 0; i < $response.length; i++) {
                var $name = $response[i].name;
                var $index = $name.toLowerCase().indexOf($typing);
                var $substr = $name.substr($index, $typing.length);

                var $html = $sce.trustAsHtml($name.replace($substr, '<span class="highlight">' + $substr + '</span>'));
                $response[i].html = $html;
            }

            return $response;
        },

        hoverItem: function(index) {
            $scope.currentIndex = index;
        },

        /**
         * Act on keypress for input field
         * @param $keycode
         * @returns {boolean}
         */
        filterTags: function ($keycode) {
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
        },

        /**
         * Todo: when the new budget tag input is focused after entering a budget,
         * todo: I don't want the dropdown to show. I had a lot of trouble and need help though.
         */
        showDropdown: function () {
            $scope.dropdown = true;
            if ($scope.typing) {
                $scope.results = $scope.highlightLetters($scope.searchLocal(), $scope.typing);
            }
        },

        searchLocal: function () {
            var $filtered_tags = _.filter($scope.tags, function ($tag) {
                return $tag.name.toLowerCase().indexOf($scope.typing.toLowerCase()) !== -1;
            });

            return $filtered_tags;
        },

        removeTag: function ($tag) {
            $scope.chosenTags = _.without($scope.chosenTags, $tag);
        },
    },
    props: [
        'chosenBudgets',
        'dropdown',
        'budgets',
        'fnOnEnter',
        'multipleBudgets',
        'model',
        'id',
        'focusOnEnter'
    ],
    ready: function () {

    }
});