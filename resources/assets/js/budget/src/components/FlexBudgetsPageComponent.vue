<template>
    <div id="flex-budgets-page" class="budgets-page">
        <edit-budget-popup
            :budgets.sync="flexBudgets"
            page="flex"
        >
        </edit-budget-popup>

        <budgets-toolbar></budgets-toolbar>

        <new-budget
            :budgets.sync="flexBudgets"
            page="flexBudgets"
        >
        </new-budget>

        <div id="budget-content">

            <totals
                :show="show"
            >
            </totals>

            <div class="budget-table flex-budget-table">

                <h1>Flex Budget Table</h1>

                <div class="order-by">
                    <div class="form-group">
                        <label for="order-by">Order By</label>

                        <select
                            v-model="orderBy"
                            id="order-by"
                            class="form-control"
                        >
                            <option
                                v-for="orderByOption in orderByOptions"
                                v-bind:value="orderByOption.value"
                            >
                                {{ orderByOption.name }}
                            </option>
                        </select>
                    </div>


                    <div class="checkbox-container">
                        <input
                            v-model="reverseOrder"
                            type="checkbox"
                        >
                        <label for="reverse-order-">Reverse Order</label>
                    </div>
                </div>

                <table id ="flex-budget-info-table" class="table table-bordered">

                    <tr>
                        <th>Name</th>
                        <th class="tooltipster" title="# percent of F/I">
                            <div>% of remaining</div>
                            <div>balance</div>
                        </th>
                        <th class="tooltipster" title="amount (% column % of F/I)">
                            <div>Calculated</div>
                            <div>amount</div>
                        </th>
                        <th class="tooltipster" title="cumulative starting date">
                            <div>Starting</div>
                            <div>date</div>
                        </th>
                        <th class="tooltipster" title="cumulative month number">
                            <div>Month</div>
                            <div>number</div>
                        </th>

                        <th class="tooltipster" title="spent before starting date">
                            <div>Spent <</div>
                            <div>starting date</div>
                        </th>

                        <th class="tooltipster" title="spent after starting date">
                            <div>Spent >=</div>
                            <div>starting date</div>
                        </th>

                        <th class="tooltipster" title="received after starting date">
                            <div>Received >=</div>
                            <div>starting date</div>
                        </th>

                        <th class="tooltipster" title="remaining">Remaining</th>
                    </tr>
                    <!-- table content -->
                    <tr v-for="budget in flexBudgets | orderBudgetsFilter" class="budget_info_ul">
                        <td v-on:click="showBudgetPopup(budget, 'flex')" class="pointer">{{ budget.name }}</td>
                        <td v-on:click="showBudgetPopup(budget, 'flex')" class="percent pointer">{{ budget.amount }}</td>
                        <td v-on:click="showBudgetPopup(budget, 'flex')" class="amount pointer">{{ budget.calculatedAmount |  numberFilter 2 }}</td>
                        <td v-on:click="showBudgetPopup(budget, 'flex')" class="CSD pointer">
                            <span>{{ budget.formattedStartingDate }}</span>
                        </td>
                        <td v-on:click="showBudgetPopup(budget, 'flex')" class="month-number pointer">{{ budget.cumulativeMonthNumber }}</td>

                        <td v-on:click="showBudgetPopup(budget, 'flex')" class="pointer">{{ budget.spentBeforeStartingDate |  numberFilter 2 }}</td>

                        <td v-on:click="showBudgetPopup(budget, 'flex')" class="pointer">{{ budget.spentOnOrAfterStartingDate |  numberFilter 2 }}</td>
                        <td v-on:click="showBudgetPopup(budget, 'flex')" class="received pointer">{{ budget.receivedOnOrAfterStartingDate |  numberFilter 2 }}</td>
                        <td v-on:click="showBudgetPopup(budget, 'flex')" v-bind:class="{'negative-remaining': budget.remaining < 0}" class="remaining pointer">{{ budget.remaining |  numberFilter 2 }}</td>
                    </tr>
                    <!-- allocated -->
                    <tr id="flex-budget-totals" class="budget_info_ul">
                        <td>allocated</td>
                        <td>{{ flexBudgetTotals.allocatedAmount |  numberFilter 2 }}</td>
                        <td>{{ flexBudgetTotals.allocatedCalculatedAmount |  numberFilter 2 }}</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>{{ flexBudgetTotals.allocatedRemaining |  numberFilter 2 }}</td>
                    </tr>
                    <!--unallocated-->
                    <tr id="flex-budget-unallocated" class="budget_info_ul">
                        <td>unallocated</td>
                        <td>{{ flexBudgetTotals.unallocatedAmount }}</td>
                        <td>{{ flexBudgetTotals.unallocatedCalculatedAmount |  numberFilter 2 }}</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>{{ flexBudgetTotals.unallocatedRemaining |  numberFilter 2 }}</td>
                    </tr>
                    <!-- flex budget totals -->
                    <tr id="flex-budget-totals" class="budget_info_ul totals">
                        <td>totals</td>
                        <td>{{ flexBudgetTotals.allocatedPlusUnallocatedAmount }}</td>
                        <td>{{ flexBudgetTotals.allocatedPlusUnallocatedCalculatedAmount |  numberFilter 2 }}</td>
                        <td>-</td>
                        <td>-</td>
                        <td>{{ flexBudgetTotals.spentBeforeStartingDate |  numberFilter 2 }}</td>
                        <td>{{ flexBudgetTotals.spentOnOrAfterStartingDate |  numberFilter 2 }}</td>
                        <td>{{ flexBudgetTotals.receivedOnOrAfterStartingDate |  numberFilter 2 }}</td>
                        <td>{{ flexBudgetTotals.allocatedPlusUnallocatedRemaining |  numberFilter 2 }}</td>
                    </tr>
                </table>
            </div>

            <span id="budget_hover_span" class="tooltipster" title=""></span>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                show: ShowRepository.defaults,
                budgetsRepository: BudgetsRepository.state,
                flexBudgetTotals: [],
                orderByOptions: [
                    {name: 'name', value: 'name'},
                    {name: 'spent after starting date', value: 'spentOnOrAfterStartingDate'}
                ],
                orderBy: 'name',
                reverseOrder: false
            };
        },
        components: {},
        computed: {
            flexBudgets: function () {
                return this.budgetsRepository.flexBudgets;
            }
        },
        filters: {
            /**
             *
             * @param number
             * @param howManyDecimals
             * @returns {Number}
             */
            numberFilter: function (number, howManyDecimals) {
                return HelpersRepository.numberFilter(number, howManyDecimals);
            },
            orderBudgetsFilter: function (budgets) {
                return BudgetsRepository.orderBudgetsFilter(budgets, this);
            },
        },
        methods: {

            /**
             *
             */
            respondToMouseEnterOnTotalsButton: function () {
                TotalsRepository.respondToMouseEnterOnTotalsButton(this);
            },

            /**
             *
             */
            respondToMouseLeaveOnTotalsButton: function () {
                TotalsRepository.respondToMouseLeaveOnTotalsButton(this);
            },

            /**
             *
             */
            toggleNewBudget: function () {
                $.event.trigger('toggle-new-budget');
            },

            /**
             *
             */
            getFlexBudgetTotals: function () {
                $.event.trigger('show-loading');
                this.$http.get('/api/totals/flexBudget', function (response) {
                    this.flexBudgetTotals = response;
                    $.event.trigger('hide-loading');
                })
                    .error(function (response) {
                        HelpersRepository.handleResponseError(response);
                    });
            },

            /**
             *
             * @param budget
             */
            showBudgetPopup: function (budget) {
                $.event.trigger('show-budget-popup', [budget]);
            },

            /**
             *
             */
            listen: function () {
                var that = this;
                $(document).on('update-flex-budget-table-totals', function (event) {
                    that.getFlexBudgetTotals();
                });
            },
        },
        props: [
            //data to be received from parent
        ],
        mounted: function () {
            BudgetsRepository.getFlexBudgets(this);
            this.getFlexBudgetTotals();
            this.listen();
        }
    }
</script>