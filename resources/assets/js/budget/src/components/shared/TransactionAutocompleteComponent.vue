<template>
    <div>

        <input
            v-model="typing"
            v-on:blur="hideAndClear()"
            v-on:focus="focus()"
            v-on:keyup="respondToKeyup($event.keyCode)"
            placeholder="{{ placeholder }}"
            id="{{ id }}"
            class="form-control"
            type='text'>

        <div v-show="showDropdown && !loading" id="{{ placeholder }}-autocomplete" class="transactions-autocomplete">

            <div v-show="results.length < 1" class="no-results">No results</div>

            <table v-show="results.length > 0" class="table table-bordered">
                <tbody
                    v-for="transaction in results"
                    v-bind:style="{color: me.preferences.colors[transaction.type]}"
                    class="pointer"
                    v-on:mousedown="chooseItem($index)"
                    v-on:mouseover="hoverItem($index)"
                    v-bind:class="{'selected': $index == currentIndex}"
                    class="dropdown-item">

                <tr>
                    <th>description</th>
                    <th>merchant</th>
                    <th>total</th>
                    <th>type</th>
                    <th>account</th>
                </tr>

                <tr>
                    <td class="description">{{ transaction.description }}</td>
                    <td class="merchant">{{ transaction.merchant }}</td>
                    <td class="total">{{ transaction.total }}</td>
                    <td class="type">{{ transaction.type }}</td>

                    <td v-if="transaction.account" class="account">{{ transaction.account.name }}</td>
                    <td v-else class="account"></td>
                </tr>

                <tr>
                    <td colspan="7">
                        <li
                            v-for="budget in transaction.budgets"
                            v-bind:class="{'tag-with-fixed-budget': budget.type === 'fixed', 'tag-with-flex-budget': budget.type === 'flex', 'tag-without-budget': budget.type === 'unassigned'}"
                            class="label label-default">
                            {{ budget.name }}
                        </li>
                    </td>
                    <!--<td colspan="1" class="budget-tag-info">{{ transaction.allocate }}</td>-->
                </tr>

                </tbody>
            </table>

        </div>
    </div>

</template>

<script>
    export default {
        data: function () {
            return {
                me: me,
                results: [],
                selectedItem: {},
                focused: false,
                showDropdown: false,
                currentIndex: 0,
                interval: '',
                timeSinceKeyPress: 0
            };
        },
        components: {},
        methods: {

            /**
             *
             */
            focus: function () {
                this.focused = true;
            },

            /**
             * Hide the dropdown and clear the input field
             */
            hideAndClear: function () {
                this.focused = false;
                this.showDropdown = false;
                this.currentIndex = null;
                $('.highlight').removeClass('highlight');
            },

            /**
             *
             * @param response
             * @param typing
             * @returns {*}
             */
            //highlightLetters: function (response, typing) {
            //    typing = typing.toLowerCase();
            //
            //    for (var i = 0; i < response.length; i++) {
            //        var name = response[i].name;
            //        var index = name.toLowerCase().indexOf(typing);
            //        var substr = name.substr(index, typing.length);
            //
            //        var html = name.replace(substr, '<span class="highlight">' + substr + '</span>');
            //
            //        response[i].html = html;
            //    }
            //    return response;
            //},

            /**
             *
             * @param index
             */
            hoverItem: function(index) {
                this.currentIndex = index;
            },

            /**
             * @param keycode
             * @returns {boolean}
             */
            respondToKeyup: function (keycode) {
                if (keycode === 13) {
                    //enter is pressed
                    if (!this.results[this.currentIndex]) {
                        //We are not choosing a transaction. We are inserting the transaction.
                        this.functionOnEnter();
                        return;
                    }
                    this.chooseItem();
                }
                else if (keycode === 38) {
                    //up arrow is pressed
                    if (this.currentIndex > 0) {
                        this.currentIndex--;
                    }
                }
                else if (keycode === 40) {
                    //down arrow is pressed
                    if (this.currentIndex + 1 < this.results.length) {
                        this.currentIndex++;
                    }
                }
                else if (keycode === 9) {
                    //tab is pressed
                    //this.hideDropdown();
                    //return false;
                }
                else {
                    //Not enter, up or down arrow
                    this.startCounting();

                    if (!this.focused) {
                        // The input is not focused anymore,
                        // so the user is not interested in the autocomplete
                        this.showDropdown = false;
                        return false;
                    }

                    if (this.timeSinceKeyPress > 1) {
                        //this.results = this.highlightLetters(this.searchDatabase(), this.typing);
                        this.searchDatabase();
                        clearInterval(this.interval);
                    }
                }
            },

            /**
             *
             */
            startCounting: function () {
                var that = this;
                clearInterval(this.interval);
                this.timeSinceKeyPress = 0;
                this.interval = setInterval(function () {
                    that.timeSinceKeyPress++;
                    that.showDropdown = true;
                    that.searchDatabase();
                }, 500);
            },

            /**
             *
             */
            fillFields: function () {
                if (this.placeholder === 'description') {
                    this.typing = this.selectedItem.description;
                    this.newTransaction.merchant = this.selectedItem.merchant;
                }
                else if (this.placeholder === 'merchant') {
                    this.typing = this.selectedItem.merchant;
                    this.newTransaction.description = this.selectedItem.description;
                }

                // If the user has the clearFields setting on,
                // only fill in the total if they haven't entered a total yet
                if (me.preferences.clearFields && this.newTransaction.total === '') {
                    this.newTransaction.total = this.selectedItem.total;
                }
                else if (!me.preferences.clearFields) {
                    this.newTransaction.total = this.selectedItem.total;
                }

                this.newTransaction.type = this.selectedItem.type;

                //It didn't work setting the whole object so I'm setting the account id and name
                this.newTransaction.account.id = this.selectedItem.account.id;
                this.newTransaction.account.name = this.selectedItem.account.name;

                // if (this.selectedItem.fromAccount && this.selectedItem.toAccount) {
                //     this.newTransaction.fromAccount = this.selectedItem.fromAccount;
                //     this.newTransaction.toAccount = this.selectedItem.toAccount;
                // }

                this.newTransaction.budgets = this.selectedItem.budgets;
            },

            /**
             *
             * @param index
             */
            chooseItem: function (index) {
                if (index !== undefined) {
                    //Item was chosen by clicking, not by pressing enter
                    this.currentIndex = index;
                }

                this.selectedItem = this.results[this.currentIndex];
                this.fillFields();
                this.hideAndClear();
            },

            /**
             * Query the database for transactions
             */
            searchDatabase: function () {
                $.event.trigger('show-loading');
                clearInterval(this.interval);
                this.$http.get('/api/transactions?column=' + this.placeholder + '&typing=' + this.typing, function (response) {
                    this.currentIndex = 0;
                    this.results = AutocompleteRepository.removeDuplicates(response);

                    $.event.trigger('hide-loading');
                })
                    .error(function (response) {
                        HelpersRepository.handleResponseError(response);
                    });
            },

        },
        props: [
            'placeholder',
            'id',
            'typing',
            'newTransaction',
            'functionOnEnter'
        ],
        mounted: function () {

        }
    }
</script>

