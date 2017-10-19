<template>
    <div class="autocomplete">

        <!--<pre>inputValue: {{$data.inputValue}}</pre>-->
        <!--<pre>selected: {{$data.selected}}</pre>-->

        <div class="form-group autocomplete-field">
            <!--Todo: capitalize filter no longer worked here after upgrade-->
            <label v-if="inputLabel" :for="inputId">{{ inputLabel }}</label>
            <input
                v-model="inputValue"
                v-on:keyup.down="downArrow()"
                v-on:keyup.up="upArrow()"
                v-on:keyup.enter="respondToEnter()"
                ref="input"
                v-on:keyup="respondToKeyup($event.keyCode)"
                v-on:focus="respondToFocus()"
                v-on:blur="respondToBlur()"
                type="text"
                :id="inputId"
                :name="inputId"
                :placeholder="inputPlaceholder"
                class="form-control autocomplete-input"
            >
            <span v-bind:class="{'dropdown-visible': dropdown}" v-on:mousedown="respondToArrowClick()" class="fa fa-caret-down"></span>
        </div>

        <div
            v-show="dropdown"
            :transition="dropdownTransition"
            class="autocomplete-dropdown scrollbar-container"
        >
            <div
                v-for="(option, index) in options"
                v-show="options.length > 0"
                v-bind:class="{'selected': currentIndex === index}"
                v-on:mouseover="hoverItem(index)"
                v-on:mousedown="selectOption(index)"
                class="autocomplete-option"
            >
                <div v-if="prop">{{ option[prop] }}</div>
                <div v-if="!prop">{{ option }}</div>

                <!--No longer works with Vue-->
                <!--<partial :name="optionPartial"></partial>-->

                <!--Delete button-->
                <button
                    v-if="deleteFunction"
                    v-on:mousedown="deleteOption(option)"
                    class="btn btn-xs btn-danger"
                >
                    Delete
                </button>

                <!--Labels for option-->
                <span v-if="option.assignedAlready && labelForOption" class="label label-default">
                        Assigned
                </span>
                <span v-if="!option.assignedAlready && labelForOption" class="label label-danger">Unassigned</span>
            </div>
            <div v-if="options.length === 0" class="no-results">No results</div>
        </div>
    </div>
</template>

<script>
    import helpers from '../../repositories/Helpers'

    export default {
        template: '#autocomplete-template',
        data: function () {
            return {
                options: [],
//                chosenOption: this.resetChosenOption(),
                dropdown: false,
                currentIndex: 0,
                timeSinceKeyPress: 0,
                interval: '',
                startedCounting: false,
                inputValue: '',
                mutableSelected: this.selected
            };
        },
        components: {},
        computed: {

        },
        watch: {
            selected () {
                this.mutableSelected = this.selected;
            },
            unfilteredOptions () {
                if (this.mutableSelected) {
                    this.setInputValue();
                }
            },
            mutableSelected () {
                this.setInputValue();
            }
        },
        methods: {

            /**
             *
             */
            hideDropdown: function () {
                this.dropdown = false;
            },

            /**
             *
             */
            showDropdown: function () {
                this.dropdown = true;
                this.currentIndex = 0;
            },

            /**
             *
             */
            upArrow: function () {
                if (this.currentIndex !== 0) {
                    this.currentIndex--;
                }
            },

            /**
             *
             */
            downArrow: function () {
                if (this.options.length - 1 !== this.currentIndex) {
                    this.currentIndex++;
                }
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
             * @param index
             */
            selectOption: function (index) {
                if (index) {
                    //Item was chosen by clicking
                    this.currentIndex = index;
                }
                this.mutableSelected = helpers.clone(this.options[this.currentIndex]);
                this.setInputValue();
                this.hideDropdown();
                this.focusNextField();
                this.functionWhenOptionIsChosen();
                this.$bus.$emit('autocomplete-option-chosen', this.mutableSelected, this.inputId);
            },

            /**
             *
             * @returns {{title: string, name: string}}
             */
//            resetChosenOption: function () {
//                return {
//                    title: '',
//                    name: ''
//                }
//            },

            /**
             *
             */
            respondToEnter: function () {
                if (this.dropdown) {
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
             */
            filterLocalOptions: function () {
                var that = this;
                return this.unfilteredOptions.filter(function (option) {
                    var string = that.getString(option);
                    return string.toLowerCase().indexOf(that.inputValue.toLowerCase()) !== -1;
                });
            },

            /**
             * If the option is an object, get the property.
             * If the option is a string, get the string.
             */
            getString: function (option) {
                if (this.prop) {
                    return option[this.prop];
                }
                console.log(option);
                return option;
            },

            /**
             *
             */
            setOptions: function (data) {
                this.options = data;
                this.showDropdown();
            },

            /**
             *
             */
            populateOptionsFromDatabase: function () {
                var url = this.url + '?filter=' + this.inputValue;
                if (this.fieldToFilterBy) {
                    url += '&field=' + this.fieldToFilterBy;
                }
                helpers.get({
                    url:  url,
                    callback: function (response) {
                        this.setOptions(response);
                    }.bind(this)
                });
            },














            /**
             * Respond to keyup if the key is a character
             * @param keycode
             */
            respondToKeyup: function (keycode) {
                if (!this.keyIsCharacter(keycode)) return false;

                if (!this.unfilteredOptions) {
                    //We'll be searching the database, so create a delay before searching
                    this.startCounting();
                }
                else {
                    //Options are local, not in the database
                    this.setOptions(this.filterLocalOptions());
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
                        that.populateOptionsFromDatabase();
                        clearInterval(that.interval);
                    }
                }, 500);
            },

            /**
             * For when the arrow is clicked
             */
//            toggleDropdown: function () {
//                if (this.dropdown) {
//                    this.respondToBlur();
//                }
//                else {
//                    this.respondToFocus();
//                }
//            },

            /**
             *
             */
            respondToArrowClick: function () {
                if(!this.dropdown) {
                    var that = this;
                    setTimeout(function () {
                        $(that.$refs.input).focus();
                    }, 100);
                }
            },

            /**
             * Show all the options if the options are local
             */
            respondToFocus: function () {
                this.clearInputValue();
                if (this.unfilteredOptions) {
                    this.setOptions(this.filterLocalOptions());
                }
            },

            /**
             *
             */
            respondToBlur: function () {
                this.hideDropdown();
                this.setInputValue();
            },

            /**
             *
             */
            clearInputValue: function () {
                this.inputValue = '';
            },

            /**
             *
             */
            setInputValue: function () {
                if (!this.mutableSelected) return false;

                if (this.prop) {
                    this.inputValue = this.mutableSelected[this.prop];
                }
                else {
                    this.inputValue = this.mutableSelected;
                }

            },

            /**
             *
             */
//            updateScrollbar: function () {
//                var dropdown = $(this.$el).find('.scrollbar-container');
//                // dropdown.scrollTop(0).perfectScrollbar('update');
//                dropdown.mCustomScrollbar("scrollTo","top");
//            },

            /**
             *
             */
            focusNextField: function () {
                if (this.idToFocusAfterAutocomplete) {
                    var that = this;
                    setTimeout(function () {
//                        $("#" + that.idToFocusAfterAutocomplete).focus();
                    }, 100);
                }
            },






            /**
             * storeProperty is the store property to set once the items are loaded.
             * loadedProperty is the store property to set once the items are loaded, to indicate that the items are loaded.
             * todo: allow for sending data: add {params:data} as second argument
             */
            get: function (options) {
                if (typeof store !== "undefined") {
                    store.showLoading();
                }

                Vue.http.get(options.url).then(function (response) {
                    if (options.callback) {
                        options.callback(response.data);
                    }

                    if (options.storeProperty) {
                        if (options.updatingArray) {
                            //Update the array the item is in
                            store.update(response.data, options.storeProperty);
                        }
                        else {
                            store.set(response.data, options.storeProperty);
                        }
                    }

                    if (options.loadedProperty) {
                        store.set(true, options.loadedProperty);
                    }

                    if (typeof store !== "undefined") {
                        store.hideLoading();
                    }
                }, function (response) {
                    helpers.handleResponseError(response.data, response.status);
                });
            },

            /**
             * Return false if key is not:
             * enter (13)
             * up (38)
             * down (40)
             * right arrow (39)
             * left arrow (37)
             * escape (27)
             * shift (16)
             * option (18)
             * control (17)
             * caps lock (20)
             */
            keyIsCharacter: function (keycode) {
                return keycode !== 13
                    && keycode !== 38
                    && keycode !== 40
                    && keycode !== 39
                    && keycode !== 37
                    && keycode !== 27
                    && keycode !== 16
                    && keycode !== 18
                    && keycode !== 17
                    && keycode !== 20;
            },
        },
        props: {
            'url': {},
            'inputLabel': {},
            'inputId': {},
            'functionOnEnter': {},
            'functionWhenOptionIsChosen': {},
            'idToFocusAfterAutocomplete': {},
            //For if items are local
            'unfilteredOptions': {},
            //Property of the chosen option to display in input field once option is chosen
            //Don't give this a default because then it will think the options are objects if they are strings
            'prop': {},
            'labelForOption': {},
            'selected': {},
            'optionPartial': {},
            'dropdownTransition': {
                default: 'fade'
            },
            inputPlaceholder: {
                default: 'Choose an option'
            },
            //For if there is a button to delete one of the options
            'deleteFunction': {},
            //For searching the database
            fieldToFilterBy: ''
        },
        ready: function () {
            var that = this;
            setTimeout(function () {
                that.setInputValue();
            }, 2000);
        },
        events: {
//            'clear-autocomplete-field': function () {
//                this.chosenOption = this.resetChosenOption();
//            }
        }
    }

</script>

<style lang="scss" rel="stylesheet/scss">
    $zIndex1: 9;
    $zIndex2: 99;
    $selected: #5cb85c;

    .autocomplete {
        margin-bottom: 20px;
        position: relative;
        .autocomplete-field {
            margin-bottom: 0px;
            position: relative;
            .fa-caret-down {
                position: absolute;
                right: 13px;
                top: 8px;
                font-size: 20px;
                cursor: pointer;
                color: #777;
                transition: transform .5s ease;
                z-index: $zIndex1;
                &.dropdown-visible {
                    transform: rotate(180deg);
                }
            }
            input {
                //To allow room for the arrow
                padding-right: 27px;
            }
        }
        .autocomplete-dropdown {
            margin-top: 2px;
            max-height: 217px;
            border: 1px solid #777;
            border-radius: 3px;
            margin-bottom: 20px;
            position: absolute;
            top: 35px;
            background: white;
            width: 100%;
            z-index: $zIndex2;
            overflow: scroll;
            .autocomplete-option {
                display: flex;
                justify-content: space-between;
                align-items: center;
                cursor: pointer;
                padding: 5px 5px;
                .label {
                    margin-top: 1px;
                    margin-right: 4px;
                }
                &.selected {
                    background: $selected;
                }
            }
            .no-results {
                padding: 3px 8px;
            }
        }
        .scrollbar-container {
            .mCSB_draggerContainer {
                right: -7px;
            }
        }
    }
</style>