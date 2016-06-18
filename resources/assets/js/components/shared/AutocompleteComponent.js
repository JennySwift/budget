var Autocomplete = {
    template: '#autocomplete-template',
    data: function () {
        return {
            autocompleteOptions: [],
            chosenOption: this.resetChosenOption(),
            showDropdown: false,
            currentIndex: 0,
            timeSinceKeyPress: 0,
            interval: '',
            startedCounting: false
        };
    },
    components: {},
    methods: {

        /**
         *
         * @returns {{title: string, name: string}}
         */
        resetChosenOption: function () {
            return {
                title: '',
                name: ''
            }
        },

        /**
         *
         * @param keycode
         */
        respondToKeyup: function (keycode) {
            if (keycode !== 13 && keycode !== 38 && keycode !== 40 && keycode !== 39 && keycode !== 37) {
                //not enter, up, down, right or left arrows
                if (!this.unfilteredAutocompleteOptions) {
                    //We'll be searching the database, so create a delay before searching
                    this.startCounting();
                }
                else {
                    this.populateOptions();
                }

            }
            else if (keycode === 38) {
                //up arrow pressed
                if (this.currentIndex !== 0) {
                    this.currentIndex--;
                }
            }
            else if (keycode === 40) {
                //down arrow pressed
                if (this.autocompleteOptions.length - 1 !== this.currentIndex) {
                    this.currentIndex++;
                }
            }
            else if (keycode === 13) {
                this.respondToEnter();
            }
        },

        /**
         * Called each time a key is pressed that would fire the request to get the results (not enter, arrows, etc)
         * So a request isn't fired each time a key is pressed if the user types quickly
         */
        startCounting: function () {
            var that = this;
            clearInterval(this.interval);
            this.timeSinceKeyPress = 0;

            this.interval = setInterval(function () {
                that.timeSinceKeyPress++;
                if (that.timeSinceKeyPress > 1) {
                    that.populateOptions();
                    clearInterval(that.interval);
                }
            }, 500);
        },

        /**
         * Show all the options if the options are local
         */
        respondToFocus: function () {
            if (this.clearFieldOnFocus) {
                this.chosenOption = this.resetChosenOption();
            }
            if (this.unfilteredAutocompleteOptions) {
                this.populateOptionsFromLocal();
            }
        },

        /**
         *
         */
        hideDropdown: function () {
            this.showDropdown = false;
        },

        /**
         *
         */
        populateOptions: function () {
            if (!this.unfilteredAutocompleteOptions) {
                this.populateOptionsFromDatabase();
            }
            else {
                this.populateOptionsFromLocal();
            }
        },

        /**
         *
         */
        populateOptionsFromLocal: function () {
            var that = this;
            this.autocompleteOptions = this.unfilteredAutocompleteOptions.filter(function (option) {
                return option[that.prop].toLowerCase().indexOf(that.chosenOption[that.prop].toLowerCase()) !== -1;
            });
            this.showDropdown = true;
            this.updateScrollbar();
            this.currentIndex = 0;
        },

        /**
         *
         */
        updateScrollbar: function () {
            var dropdown = $(this.$el).find('.scrollbar-container');
            // dropdown.scrollTop(0).perfectScrollbar('update');
            dropdown.mCustomScrollbar("scrollTo","top");
        },

        /**
         *
         */
        populateOptionsFromDatabase: function () {
            $.event.trigger('show-loading');
            this.$http.get(this.url + '?filter=' + this.chosenOption[this.prop], function (response) {
                this.autocompleteOptions = response;
                this.showDropdown = true;
                this.updateScrollbar();

                this.currentIndex = 0;
                $.event.trigger('hide-loading');
            })
                .error(function (response) {
                    HelpersRepository.handleResponseError(response);
                });
        },

        /**
         *
         */
        respondToEnter: function () {
            if (this.showDropdown) {
                //enter is for the autocomplete
                this.selectOption();
            }
            else {
                //enter is to add the entry
                this.functionOnEnter();
            }
        },

        /**
         *
         * @param index
         */
        selectOption: function (index) {
            if (index) {
                //Item was chosen by clicking
                this.currentIndex = index;
            }
            this.chosenOption = HelpersRepository.clone(this.autocompleteOptions[this.currentIndex]);
            this.showDropdown = false;
            if (this.idToFocusAfterAutocomplete) {
                var that = this;
                setTimeout(function () {
                    $("#" + that.idToFocusAfterAutocomplete).focus();
                }, 100);
            }
            this.$dispatch('option-chosen', this.chosenOption);
            this.model = this.chosenOption;
            this.functionWhenOptionIsChosen();

            this.$nextTick(function () {
                $(this.$els.inputField).blur();
            });
        },

        /**
         *
         * @param index
         */
        hoverItem: function(index) {
            this.currentIndex = index;
        },

        /**
         *
         * @param option
         */
        deleteOption: function (option) {
            if (confirm("Are you sure?")) {
                this.deleteFunction(option);
                var index = HelpersRepository.findIndexById(this.autocompleteOptions, option.id);
                this.autocompleteOptions = _.without(this.autocompleteOptions, this.autocompleteOptions[index]);
            }
        },

        /**
         * So that sometimes I can have a delete button that doesn't fire the selectOption event when pressed,
         * and when I don't have a delete button, the selectOption method does get fired if clicked anywhere in the div.
         * @param index
         */
        respondToMouseDownOnOption: function (index) {
            if (!this.deleteFunction) {
                this.selectOption(index);
            }
        },

        /**
         * Only fire the selectOption method if there is a delete button,
         * because otherwise the method is fired when the parent is clicked
         * @param index
         */
        respondToMouseDownOnText: function (index) {
            if (this.deleteFunction) {
                this.selectOption(index);
            }
        },

        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            $.event.trigger('response-error', [response]);
            this.showLoading = false;
        }
    },
    props: [
        'url',
        'inputLabel',
        'autocompleteId',
        'autocompleteFieldId',
        'functionOnEnter',
        'functionWhenOptionIsChosen',
        //For if there is a button to delete one of the options
        'deleteFunction',
        'idToFocusAfterAutocomplete',
        'model',
        //For if items are local
        'unfilteredAutocompleteOptions',
        //Property of the chosen option to display in input field once option is chosen
        'prop',
        'labelForOption',
        'inputPlaceholder',
        'clearFieldOnFocus'
    ],
    events: {
        'clear-autocomplete-field': function () {
            this.chosenOption = this.resetChosenOption();
        }
    },
    ready: function () {
        HelpersRepository.scrollbars();
    }
};

//I'm separating this here from the data above so I can test the component
Vue.component('autocomplete', Autocomplete);
