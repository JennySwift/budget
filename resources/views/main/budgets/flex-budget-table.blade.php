
<h1>Flex Budget Table</h1>

<table id ="flex-budget-info-table" class="table table-bordered">

    <tr>
        <th>Name</th>
        <th class="tooltipster" title="# percent of F/I">Amount</th>
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

        <th class="tooltipster" title="remaining">Remaining</th>

        <th>
            <i class="fa fa-times"></i>
        </th>
    </tr>
    <!-- table content -->
    <tr v-for="budget in flexBudgets | orderBy 'name'" class="budget_info_ul">
        <td v-on:click="showBudgetPopup(budget, 'flex')" class="pointer">@{{ budget.name }}</td>
        <td v-on:click="showBudgetPopup(budget, 'flex')" class="percent pointer">@{{ budget.amount }}</td>
        <td v-on:click="showBudgetPopup(budget, 'flex')" class="amount pointer">@{{ budget.calculatedAmount |  numberFilter 2 }}</td>
        <td v-on:click="showBudgetPopup(budget, 'flex')" class="CSD pointer">
            <span>@{{ budget.formattedStartingDate }}</span>
        </td>
        <td v-on:click="showBudgetPopup(budget, 'flex')" class="month-number pointer">@{{ budget.cumulativeMonthNumber }}</td>

        <td v-on:click="showBudgetPopup(budget, 'flex')" class="pointer">@{{ budget.spentBeforeStartingDate |  numberFilter 2 }}</td>

        <td v-on:click="showBudgetPopup(budget, 'flex')" class="pointer">@{{ budget.spentAfterStartingDate |  numberFilter 2 }}</td>
        <td v-on:click="showBudgetPopup(budget, 'flex')" class="received pointer">@{{ budget.receivedAfterStartingDate |  numberFilter 2 }}</td>
        <td v-on:click="showBudgetPopup(budget, 'flex')" class="remaining pointer">@{{ budget.remaining |  numberFilter 2 }}</td>
        <td>
            <button v-on:click="deleteBudget(budget)" class="btn btn-xs btn-danger">delete</button>
        </td>
    </tr>
    <!-- allocated -->
    <tr id="flex-budget-totals" class="budget_info_ul">
        <td>allocated</td>
        <td>@{{ flexBudgetTotals.allocatedAmount |  numberFilter 2 }}</td>
        <td>@{{ flexBudgetTotals.allocatedCalculatedAmount |  numberFilter 2 }}</td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
        <td>@{{ flexBudgetTotals.allocatedRemaining |  numberFilter 2 }}</td>
        <td>-</td>
    </tr>
    {{--unallocated--}}
    <tr id="flex-budget-unallocated" class="budget_info_ul">
        <td>unallocated</td>
        <td>@{{ flexBudgetTotals.unallocatedAmount }}</td>
        <td>@{{ flexBudgetTotals.unallocatedCalculatedAmount |  numberFilter 2 }}</td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
        <td>@{{ flexBudgetTotals.unallocatedRemaining |  numberFilter 2 }}</td>
        <td>-</td>
    </tr>
    <!-- flex budget totals -->
    <tr id="flex-budget-totals" class="budget_info_ul totals">
        <td>totals</td>
        <td>@{{ flexBudgetTotals.allocatedPlusUnallocatedAmount }}</td>
        <td>@{{ flexBudgetTotals.allocatedPlusUnallocatedCalculatedAmount |  numberFilter 2 }}</td>
        <td>-</td>
        <td>-</td>
        <td>@{{ flexBudgetTotals.spentBeforeStartingDate |  numberFilter 2 }}</td>
        <td>@{{ flexBudgetTotals.spentAfterStartingDate |  numberFilter 2 }}</td>
        <td>@{{ flexBudgetTotals.receivedAfterStartingDate |  numberFilter 2 }}</td>
        <td>@{{ flexBudgetTotals.allocatedPlusUnallocatedRemaining |  numberFilter 2 }}</td>
        <td>-</td>
    </tr>
</table>