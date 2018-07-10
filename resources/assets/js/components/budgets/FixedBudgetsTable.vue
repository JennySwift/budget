<template>
    <div>
        <h1>Fixed Budget Table</h1>

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

        <table id="fixed-budget-info-table" class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>
                        <div>Amount</div>
                        <div>per month</div>
                    </th>
                    <th>
                        <div>Starting</div>
                        <div>date</div>
                    </th>
                    <th>
                        <div>Month</div>
                        <div>number</div>
                    </th>
                    <th class="tooltipster" title="cumulative (amount * cumulative month number)">
                        <div>Cumulative</div>
                        <div>amount</div>
                    </th>

                    <th>
                        <div>Spent <</div>
                        <div>starting date</div>
                    </th>

                    <th>
                        <div>Spent >=</div>
                        <div>starting date</div>
                    </th>

                    <th>
                        <div>Received >=</div>
                        <div>starting date</div>
                    </th>

                    <th class="tooltipster" title="remaining  (cumulative + spent + received)">Remaining</th>
                </tr>
            </thead>
            <!-- table content -->
            <tbody>
                <tr v-for="budget in orderedFixedBudgets" class="budget_info_ul">

                    <td v-on:click="showBudgetPopup(budget, 'fixed')" class="pointer">{{ budget.name }}</td>

                    <td v-on:click="showBudgetPopup(budget, 'fixed')" class="amount right pointer">{{ budget.amount | numberFilter(2) }}</td>

                    <td v-on:click="showBudgetPopup(budget, 'fixed')" class="CSD pointer">
                        <span>{{ budget.formattedStartingDate }}</span>
                    </td>

                    <td v-on:click="showBudgetPopup(budget, 'fixed')" class="month-number pointer">{{ budget.cumulativeMonthNumber }}</td>

                    <td v-on:click="showBudgetPopup(budget, 'fixed')" class="cumulative pointer">{{ budget.cumulative | numberFilter(2) }}</td>

                    <td v-on:click="showBudgetPopup(budget, 'fixed')" class="pointer">{{ budget.spentBeforeStartingDate | numberFilter(2) }}</td>

                    <td v-on:click="showBudgetPopup(budget, 'fixed')" class="pointer">{{ budget.spentOnOrAfterStartingDate | numberFilter(2) }}</td>

                    <td v-on:click="showBudgetPopup(budget, 'fixed')" class="received pointer">{{ budget.receivedOnOrAfterStartingDate | numberFilter(2) }}</td>

                    <td v-on:click="showBudgetPopup(budget, 'fixed')" v-bind:class="{'negative-remaining': budget.remaining < 0}" class="remaining pointer">{{ budget.remaining | numberFilter(2) }}</td>

                </tr>

                <!-- fixed budget totals -->
                <tr id="fixed-budget-totals" class="budget_info_ul totals">
                    <td>totals</td>
                    <td>{{ shared.fixedBudgetTotals.amount | numberFilter(2) }}</td>
                    <td>-</td>
                    <td>-</td>
                    <td>{{ shared.fixedBudgetTotals.cumulative | numberFilter(2) }}</td>
                    <td>{{ shared.fixedBudgetTotals.spentBeforeStartingDate | numberFilter(2) }}</td>
                    <td>{{ shared.fixedBudgetTotals.spentOnOrAfterStartingDate | numberFilter(2) }}</td>
                    <td>{{ shared.fixedBudgetTotals.receivedOnOrAfterStartingDate | numberFilter(2) }}</td>
                    <td>{{ shared.fixedBudgetTotals.remaining | numberFilter(2) }}</td>
                </tr>

            </tbody>
        </table>
    </div>
</template>

<script>
    import helpers from '../../repositories/Helpers'
//    import store from '../../repositories/Store'
    export default {
        data: function () {
            return {
                shared: store.state,
                orderByOptions: [
                    {name: 'name', value: 'name'},
                    {name: 'spent after starting date', value: 'spentOnOrAfterStartingDate'}
                ],
                orderBy: 'name',
                reverseOrder: false
            };
        },
        computed: {
            orderedFixedBudgets: function () {
              return store.orderBudgetsFilter(this.shared.fixedBudgets, this);
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
        }
    }
</script>