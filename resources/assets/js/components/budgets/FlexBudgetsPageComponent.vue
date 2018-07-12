<template>
    <div id="flex-budgets-page" class="budgets-page">
        <budget-popup
            page="flex"
        >
        </budget-popup>

        <budgets-toolbar></budgets-toolbar>

        <new-budget
            page="flexBudgets"
        >
        </new-budget>

        <div id="budget-content">

            <totals></totals>

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
                    <thead>
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
                    </thead>
                    <tbody>
                        <tr v-for="budget in orderedFlexBudgets" class="budget_info_ul">
                            <td v-on:click="showBudgetPopup(budget, 'flex')" class="pointer">{{ budget.name }}</td>
                            <td v-on:click="showBudgetPopup(budget, 'flex')" class="percent pointer">{{ budget.amount }}</td>
                            <td v-on:click="showBudgetPopup(budget, 'flex')" class="amount pointer">{{ budget.calculatedAmount |  numberFilter(2) }}</td>
                            <td v-on:click="showBudgetPopup(budget, 'flex')" class="CSD pointer">
                                <span>{{ budget.formattedStartingDate }}</span>
                            </td>
                            <td v-on:click="showBudgetPopup(budget, 'flex')" class="month-number pointer">{{ budget.cumulativeMonthNumber }}</td>

                            <td v-on:click="showBudgetPopup(budget, 'flex')" class="pointer">{{ budget.spentBeforeStartingDate |  numberFilter(2) }}</td>

                            <td v-on:click="showBudgetPopup(budget, 'flex')" class="pointer">{{ budget.spentOnOrAfterStartingDate |  numberFilter(2) }}</td>
                            <td v-on:click="showBudgetPopup(budget, 'flex')" class="received pointer">{{ budget.receivedOnOrAfterStartingDate |  numberFilter(2) }}</td>
                            <td v-on:click="showBudgetPopup(budget, 'flex')" v-bind:class="{'negative-remaining': budget.remaining < 0}" class="remaining pointer">{{ budget.remaining |  numberFilter(2) }}</td>
                        </tr>
                        <!-- allocated -->
                        <tr id="flex-budget-totals" class="budget_info_ul">
                            <td>allocated</td>
                            <td>{{ shared.flexBudgetTotals.allocatedAmount |  numberFilter(2) }}</td>
                            <td>{{ shared.flexBudgetTotals.allocatedCalculatedAmount |  numberFilter(2) }}</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>{{ shared.flexBudgetTotals.allocatedRemaining |  numberFilter(2) }}</td>
                        </tr>
                        <!--unallocated-->
                        <tr id="flex-budget-unallocated" class="budget_info_ul">
                            <td>unallocated</td>
                            <td>{{ shared.flexBudgetTotals.unallocatedAmount }}</td>
                            <td>{{ shared.flexBudgetTotals.unallocatedCalculatedAmount |  numberFilter(2) }}</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>{{ shared.flexBudgetTotals.unallocatedRemaining |  numberFilter(2) }}</td>
                        </tr>
                        <!-- flex budget totals -->
                        <tr id="flex-budget-totals" class="budget_info_ul totals">
                            <td>totals</td>
                            <td>{{ shared.flexBudgetTotals.allocatedPlusUnallocatedAmount }}</td>
                            <td>{{ shared.flexBudgetTotals.allocatedPlusUnallocatedCalculatedAmount |  numberFilter(2) }}</td>
                            <td>-</td>
                            <td>-</td>
                            <td>{{ shared.flexBudgetTotals.spentBeforeStartingDate |  numberFilter(2) }}</td>
                            <td>{{ shared.flexBudgetTotals.spentOnOrAfterStartingDate |  numberFilter(2) }}</td>
                            <td>{{ shared.flexBudgetTotals.receivedOnOrAfterStartingDate |  numberFilter(2) }}</td>
                            <td>{{ shared.flexBudgetTotals.allocatedPlusUnallocatedRemaining |  numberFilter(2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <span id="budget_hover_span" class="tooltipster" title=""></span>
        </div>
    </div>
</template>

<script>
    import TotalsRepository from '../../repositories/TotalsRepository'
    import helpers from '../../repositories/helpers/Helpers.js'
    import TotalsComponent from '../../components/TotalsComponent.vue'
    import NewBudgetComponent from '../../components/budgets/NewBudgetComponent.vue'
    import BudgetsToolbarComponent from '../../components/shared/BudgetsToolbarComponent.vue'
    import BudgetPopupComponent from '../../components/budgets/BudgetPopupComponent.vue'

    export default {
        data: function () {
            return {
                show: store.state.show.defaults,
                shared: store.state,
                orderByOptions: [
                    {name: 'name', value: 'name'},
                    {name: 'spent after starting date', value: 'spentOnOrAfterStartingDate'}
                ],
                orderBy: 'name',
                reverseOrder: false
            };
        },
        components: {
            'totals': TotalsComponent,
            'new-budget': NewBudgetComponent,
            'budgets-toolbar': BudgetsToolbarComponent,
            'budget-popup': BudgetPopupComponent
        },
        computed: {
            orderedFlexBudgets: function () {
                return store.orderBudgetsFilter(this.shared.flexBudgets, this);
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
                return helpers.numberFilter(number, howManyDecimals);
            }
        },
        methods: {
            /**
             *
             * @param budget
             */
            showBudgetPopup: function (budget) {
                store.set(budget, 'selectedBudget');
                helpers.showPopup('budget');
            },
        },
        mounted: function () {
            store.getFlexBudgets(this);
            store.getFlexBudgetTotals();
        }
    }
</script>