
<h1>Fixed Budget Table</h1>

<table id="fixed-budget-info-table" class="table table-bordered">

    <tr>
        <th>Name</th>
        <th class="tooltipster" title="amount">Amount</th>
        <th class="tooltipster" title="cumulative starting date">
            <div>Starting</div>
            <div>date</div>
        </th>
        <th class="tooltipster" title="cumulative month number">
            <div>Month</div>
            <div>number</div>
        </th>
        <th class="tooltipster" title="cumulative (amount * cumulative month number)">
            <div>Cumulative</div>
            <div>amount</div>
        </th>

        <th class="tooltipster" title="spent before starting date">
            <div>Spent before</div>
            <div>starting date</div>
        </th>

        <th class="tooltipster" title="spent after starting date">
            <div>Spent after</div>
            <div>starting date</div>
        </th>

        <th class="tooltipster" title="received after starting date">
            <div>Received after</div>
            <div>starting date</div>
        </th>

        <th class="tooltipster" title="remaining  (cumulative + spent + received)">Remaining</th>

        <th>
            <i class="fa fa-times"></i>
        </th>
    </tr>

    <!-- table content -->
    <tr v-repeat="budget in fixedBudgets | orderBy: 'name'" class="budget_info_ul">

        <td v-on:click="showBudgetPopup(budget, 'fixed')" class="pointer">[[budget.name]]</td>

        <td v-on:click="showBudgetPopup(budget, 'fixed')" class="amount right pointer">[[budget.amount | number:2]]</td>

        <td v-on:click="showBudgetPopup(budget, 'fixed')" class="CSD pointer">
            <span>[[budget.formattedStartingDate]]</span>
        </td>

        <td v-on:click="showBudgetPopup(budget, 'fixed')" class="month-number pointer">[[budget.cumulativeMonthNumber]]</td>

        <td v-on:click="showBudgetPopup(budget, 'fixed')" class="cumulative pointer">[[budget.cumulative | number:2]]</td>

        <td v-on:click="showBudgetPopup(budget, 'fixed')" class="pointer">[[budget.spentBeforeStartingDate | number:2]]</td>

        <td v-on:click="showBudgetPopup(budget, 'fixed')" class="pointer">[[budget.spentAfterStartingDate | number:2]]</td>

        <td v-on:click="showBudgetPopup(budget, 'fixed')" class="received pointer">[[budget.receivedAfterStartingDate | number:2]]</td>

        <td v-on:click="showBudgetPopup(budget, 'fixed')" class="remaining pointer">[[budget.remaining | number:2]]</td>

        <td>
            <button v-on:click="deleteBudget(budget)" class="btn btn-xs btn-danger">delete</button>
        </td>

    </tr>

    <!-- fixed budget totals -->
    <tr id="fixed-budget-totals" class="budget_info_ul totals">
        <td>totals</td>
        <td>[[fixedBudgetTotals.amount | number:2]]</td>
        <td>-</td>
        <td>-</td>
        <td>[[fixedBudgetTotals.cumulative | number:2]]</td>
        <td>[[fixedBudgetTotals.spentBeforeStartingDate | number:2]]</td>
        <td>[[fixedBudgetTotals.spentAfterStartingDate | number:2]]</td>
        <td>[[fixedBudgetTotals.receivedAfterStartingDate | number:2]]</td>
        <td>[[fixedBudgetTotals.remaining | number:2]]</td>
        <td>-</td>
    </tr>

</table>