<template>
    <div>
        <div
            v-slide="showContent"
            class="section"
        >

            <h4 v-on:click="showContent = !showContent" class="center">budgets</h4>

            <div class="content">

                <div v-show="filterTab === 'show'">

                    <div class="form-group">

                        <label>Transactions will contain all of the budgets entered here</label>

                        <div class="input-btn-container">
                            <budget-autocomplete
                                :chosen-budgets.sync="filter.budgets.in.and"
                                :budgets="budgets"
                                multiple-budgets="true"
                            >
                            </budget-autocomplete>

                            <span class="input-group-btn">
                            <button v-on:click="clearBudgetField('in', 'and')" class="clear-search-button btn btn-default">clear</button>
                        </span>
                        </div>

                    </div>

                    <div class="form-group">

                        <label>Transactions will contain at least one of the budgets entered here</label>

                        <div
                            class="input-btn-container"
                        >
                            <budget-autocomplete
                                :chosen-budgets.sync="filter.budgets.in.or"
                                :budgets="budgets"
                                multiple-budgets="true"
                            >
                            </budget-autocomplete>
                            <span class="input-group-btn">
                            <button v-on:click="clearBudgetField('in', 'or')" class="clear-search-button btn btn-default">clear</button>
                        </span>
                        </div>

                    </div>

                </div>

                <div v-show="filterTab === 'hide'">

                    <div class="form-group">

                        <label>Transactions will contain none of the budgets entered here</label>

                        <div class="input-btn-container">
                            <budget-autocomplete
                                :chosen-budgets.sync="filter.budgets.out"
                                :budgets="budgets"
                                multiple-budgets="true"
                            >
                            </budget-autocomplete>

                            <span class="input-group-btn">
                        <button v-on:click="clearBudgetField('out')" class="clear-search-button btn btn-default">clear</button>
                    </span>
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>

</template>

<script>
    import FilterRepository from '../../repositories/FilterRepository'
    export default {
        data: function () {
            return {
                showContent: false
            };
        },
        components: {},
        methods: {
            /**
             * type1 is 'in' or 'out'.
             * type2 is 'and' or 'or'.
             * @param type1
             * @param type2
             */
            clearBudgetField: function (type1, type2) {
                FilterRepository.clearBudgetField(this, type1, type2);
            }

        },
        props: [
            'filter',
            'filterTab',
            'runFilter',
            'budgets'
        ],
        mounted: function () {

        },
        events: {
            'budget-chosen': function () {
                this.runFilter();
            },
            'budget-removed': function () {
                this.runFilter();
            }
        }
    }
</script>