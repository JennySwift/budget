var TransactionAutocomplete = Vue.component('transaction-autocomplete', {
    template: '#transaction-autocomplete-template',
    data: function () {
        return {
            results: []
        };
    },
    components: {},
    methods: {
        focus: function () {
            $scope.focused = true;
        },

        /**
         * Hide the dropdown and clear the input field
         */
        hideAndClear: function () {
            $scope.focused = false;
            $scope.hideDropdown();
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
        filter: function ($keycode) {
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
            else if ($keycode === 9) {
                //tab is pressed
                //$scope.hideDropdown();
                //return false;
            }
            else {
                console.log('focused');
                //Not enter, up or down arrow
                $scope.startCounting();
                $scope.currentIndex = 0;
                $scope.showDropdown();
            }
        },

        startCounting: function () {
            $interval.cancel($scope.interval);
            $scope.timeSinceKeyPress = 0;
            $scope.interval = $interval(function () {
                $scope.timeSinceKeyPress++;
                $scope.showDropdown();
            }, 500);
        },

        fillFields: function () {
            if ($scope.placeholder === 'description') {
                $scope.typing = $scope.selectedItem.description;
                $scope.new_transaction.merchant = $scope.selectedItem.merchant;
            }
            else if ($scope.placeholder === 'merchant') {
                $scope.typing = $scope.selectedItem.merchant;
                $scope.new_transaction.description = $scope.selectedItem.description;
            }

            // If the user has the clearFields setting on,
            // only fill in the total if they haven't entered a total yet
            if (me.preferences.clearFields && $scope.new_transaction.total === '') {
                $scope.new_transaction.total = $scope.selectedItem.total;
            }
            else if (!me.preferences.clearFields) {
                $scope.new_transaction.total = $scope.selectedItem.total;
            }

            $scope.new_transaction.type = $scope.selectedItem.type;
            $scope.new_transaction.account_id = $scope.selectedItem.account.id;

            if ($scope.selectedItem.from_account && $scope.selectedItem.to_account) {
                $scope.new_transaction.from_account_id = $scope.selectedItem.from_account.id;
                $scope.new_transaction.to_account_id = $scope.selectedItem.to_account.id;
            }

            $scope.new_transaction.budgets = $scope.selectedItem.budgets;
        },

        chooseItem: function ($index) {
            if ($index !== undefined) {
                //Item was chosen by clicking, not by pressing enter
                $scope.currentIndex = $index;
            }

            $scope.selectedItem = $scope.results[$scope.currentIndex];

            $scope.fillFields();

            $scope.hideAndClear();
        },

        showDropdown: function () {
            $scope.dropdown = true;

            if (!$scope.focused) {
                // The input is not focused anymore,
                // so the user is not interested in the autocomplete
                $scope.hideDropdown();
                return false;
            }

            if ($scope.timeSinceKeyPress > 1) {
                $scope.results = $scope.highlightLetters($scope.searchDatabase(), $scope.typing);
                $interval.cancel($scope.interval);
            }
        },


        searchLocal: function () {
            var $results = _.filter($scope.tags, function ($tag) {
                return $tag.name.toLowerCase().indexOf($scope.typing.toLowerCase()) !== -1;
            });

            return $results;
        },

        /**
         * Query the database
         */
        searchDatabase: function () {
            $scope.showLoading();
            return $http.get('/api/transactions?column=' + $scope.placeholder + '&typing=' + $scope.typing).
            success(function (response, status, headers, config) {
                $scope.results = AutocompleteFactory.transferTransactions(response);
                $scope.results = AutocompleteFactory.removeDuplicates($scope.results);
                $scope.hideLoading();
            }).
            error(function (data, status, headers, config) {
                console.log("There was an error");
            });
        },


    },
    props: [
        'dropdown',
        'placeholder',
        'id',
        'typing',
        'newTransaction',
        'fnOnEnter',
        'showLoading',
        'hideLoading',
        'loading'
    ],
    ready: function () {

    }
});

