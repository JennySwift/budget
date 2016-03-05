<script id="budgets-filter-template" type="x-template">

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
                                :function-on-enter="insertTransaction"
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
                                :function-on-enter="insertTransaction"
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

                    <budget-autocomplete
                            :chosen-budgets.sync="filter.budgets.out"
                            :budgets="budgets"
                            multiple-budgets="true"
                            :function-on-enter="insertTransaction"
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

</script>