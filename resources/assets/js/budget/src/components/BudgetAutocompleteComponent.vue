<template>
    <div class="budget-wrapper">
        <div class="budget-input-wrapper">

            <input
                v-model="typing"
                v-on:focus="showDropdown = true"
                v-on:blur="showDropdown = false"
                v-on:keyup="filterBudgets($event.keyCode)"
                placeholder="budgets"
                type='text'
                id="@{{ id }}-input">

            <div v-show="showDropdown" class="budget-dropdown">
                <div
                    v-for="budget in results"
                    v-on:mousedown="chooseBudget($index)"
                    v-on:mouseover="hoverItem($index)"
                    v-bind:class="{'selected': $index == currentIndex}"
                    class="dropdown-item">
                    <div v-html="budget.html"></div>
                    <div>
                        <span class="label label-default @{{ budget.type }}-label">@{{ budget.type }}</span>
                    </div>
                </div>
            </div>

        </div>

        <div v-cloak v-if="chosenBudgets" v-show="chosenBudgets.length > 0" class="budget-display">

            <li
                v-for="budget in chosenBudgets"
                v-on:click="removeBudget(budget)"
                v-bind:class="{
                        'tag-with-fixed-budget': budget.type === 'fixed',
                        'tag-with-flex-budget': budget.type === 'flex',
                        'tag-without-budget': budget.type === 'unassigned'
                    }"
                class="label label-default removable-budget"
            >
                <span>@{{ budget.name }}</span>
                <span class="type">@{{ budget.type }}</span>
                <i class="fa fa-times"></i>
            </li>

        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                results: [],
                messages: {},
                currentIndex: 0,
                typing: '',
                showDropdown: false
            };
        },
        components: {},
        methods: {

            /**
             * Check for duplicate budgets when adding a new budget to an array
             * @param budgetId
             * @param budgetArray
             * @returns {boolean}
             */
            duplicateBudgetCheck: function (budgetId, budgetArray) {
                for (var i = 0; i < budgetArray.length; i++) {
                    if (budgetArray[i].id === budgetId) {
                        return false; //it is a duplicate
                    }
                }
                return true; //it is not a duplicate
            },

            /**
             *
             * @param index
             */
            chooseBudget: function (index) {
                if (index !== undefined) {
                    //Item was chosen by clicking, not by pressing enter
                    this.currentIndex = index;
                }

                if (this.multipleBudgets) {
                    this.addBudget();
                }
                else {
                    this.fillField();
                }
            },

            /**
             * For if only one budget can be chosen
             */
            fillField: function () {
                this.typing = this.results[this.currentIndex].name;
                this.model = this.results[this.currentIndex];
                if (this.focusOnEnter) {
                    // Todo: This line doesn't work if budget is chosen with mouse click
                    $("#" + this.focusOnEnter).focus();
                }
                this.hideAndClear();
            },

            /**
             * For if multiple budgets can be chosen
             */
            addBudget: function () {
                var budgetId = this.results[this.currentIndex].id;

                if (!this.duplicateBudgetCheck(budgetId, this.chosenBudgets)) {
                    //$rootScope.$broadcast('provideFeedback', 'You have already entered that budget');
                    this.hideAndClear();
                    return;
                }

                this.chosenBudgets.push(this.results[this.currentIndex]);
                this.$dispatch('budget-chosen');
                this.hideAndClear();
            },

            /**
             * Hide the dropdown and clear the input field
             */
            hideAndClear: function () {
                this.showDropdown = false;

                if (this.multipleBudgets) {
                    this.typing = '';
                }

                this.currentIndex = null;
                $('.highlight').removeClass('highlight');
            },

            /**
             *
             * @param response
             * @param typing
             * @returns {*}
             */
            highlightLetters: function (response, typing) {
                typing = typing.toLowerCase();

                for (var i = 0; i < response.length; i++) {
                    var name = response[i].name;
                    var index = name.toLowerCase().indexOf(typing);
                    var substr = name.substr(index, typing.length);

                    //var html = $sce.trustAsHtml(name.replace(substr, '<span class="highlight">' + substr + '</span>'));
                    var html = name.replace(substr, '<span class="highlight">' + substr + '</span>');
                    response[i].html = html;
                }

                return response;
            },

            /**
             *
             * @param index
             */
            hoverItem: function(index) {
                this.currentIndex = index;
            },

            /**
             * Act on keypress for input field
             * @param keycode
             * @returns {boolean}
             */
            filterBudgets: function (keycode) {
                if (keycode === 13) {
                    //enter is pressed
                    //this.chooseItem();

                    if (!this.results[this.currentIndex]) {
                        //We are not adding a budget. We are inserting the transaction.
                        this.functionOnEnter();
                        return;
                    }
                    //We are choosing a budget
                    this.chooseBudget();

                    //resetting the dropdown to show all the budgets again after a budget has been added
                    this.results = this.budgets;
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
                else {
                    //Not enter, up or down arrow
                    this.currentIndex = 0;
                    // Todo: when the new budget budget input is focused after entering a budget,
                    // todo: I don't want the dropdown to show. I had a lot of trouble and need help though.
                    this.showDropdown = true;
                    if (this.typing) {
                        this.results = this.highlightLetters(this.searchLocal(), this.typing);
                    }
                }
            },

            /**
             *
             * @returns {*}
             */
            searchLocal: function () {
                var that = this;
                var filteredBudgets = _.filter(this.budgets, function (budget) {
                    return budget.name.toLowerCase().indexOf(that.typing.toLowerCase()) !== -1;
                });

                return filteredBudgets;
            },

            /**
             *
             * @param budget
             */
            removeBudget: function (budget) {
                this.chosenBudgets = _.without(this.chosenBudgets, budget);
                this.$dispatch('budget-removed');
            },
        },
        props: [
            'chosenBudgets',
            //'dropdown',
            'budgets',
            'functionOnEnter',
            'multipleBudgets',
            'model',
            'id',
            'focusOnEnter'
        ],
        mounted: function () {

        }

    }
</script>