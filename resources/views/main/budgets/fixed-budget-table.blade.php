
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
                @{{ orderByOption.name }}
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
            <div>Spent before</div>
            <div>starting date</div>
        </th>

        <th>
            <div>Spent after</div>
            <div>starting date</div>
        </th>

        <th>
            <div>Received after</div>
            <div>starting date</div>
        </th>

        <th class="tooltipster" title="remaining  (cumulative + spent + received)">Remaining</th>
    </tr>

    <!-- table content -->
    <tr v-for="budget in fixedBudgets | orderBudgetsFilter" class="budget_info_ul">

        <td v-on:click="showBudgetPopup(budget, 'fixed')" class="pointer">@{{ budget.name }}</td>

        <td v-on:click="showBudgetPopup(budget, 'fixed')" class="amount right pointer">@{{ budget.amount | numberFilter 2 }}</td>

        <td v-on:click="showBudgetPopup(budget, 'fixed')" class="CSD pointer">
            <span>@{{ budget.formattedStartingDate }}</span>
        </td>

        <td v-on:click="showBudgetPopup(budget, 'fixed')" class="month-number pointer">@{{ budget.cumulativeMonthNumber }}</td>

        <td v-on:click="showBudgetPopup(budget, 'fixed')" class="cumulative pointer">@{{ budget.cumulative | numberFilter 2 }}</td>

        <td v-on:click="showBudgetPopup(budget, 'fixed')" class="pointer">@{{ budget.spentBeforeStartingDate | numberFilter 2 }}</td>

        <td v-on:click="showBudgetPopup(budget, 'fixed')" class="pointer">@{{ budget.spentAfterStartingDate | numberFilter 2 }}</td>

        <td v-on:click="showBudgetPopup(budget, 'fixed')" class="received pointer">@{{ budget.receivedAfterStartingDate | numberFilter 2 }}</td>

        <td v-on:click="showBudgetPopup(budget, 'fixed')" v-bind:class="{'negative-remaining': budget.remaining < 0}" class="remaining pointer">@{{ budget.remaining | numberFilter 2 }}</td>

    </tr>

    <!-- fixed budget totals -->
    <tr id="fixed-budget-totals" class="budget_info_ul totals">
        <td>totals</td>
        <td>@{{ fixedBudgetTotals.amount | numberFilter 2 }}</td>
        <td>-</td>
        <td>-</td>
        <td>@{{ fixedBudgetTotals.cumulative | numberFilter 2 }}</td>
        <td>@{{ fixedBudgetTotals.spentBeforeStartingDate | numberFilter 2 }}</td>
        <td>@{{ fixedBudgetTotals.spentAfterStartingDate | numberFilter 2 }}</td>
        <td>@{{ fixedBudgetTotals.receivedAfterStartingDate | numberFilter 2 }}</td>
        <td>@{{ fixedBudgetTotals.remaining | numberFilter 2 }}</td>
    </tr>

</table>