
<h1>Flex Budget Table</h1>

<table id ="flex-budget-info-table" class="table table-bordered">

    <tr>
        <th>Name</th>
        <th class="tooltipster" title="# percent of F/I">%</th>
        <th class="tooltipster" title="amount (% column % of F/I)">A</th>
        <th class="tooltipster" title="cumulative starting date">SD</th>
        <th class="tooltipster" title="cumulative month number">CMN</th>

        <th class="tooltipster" title="spent before starting date">
            <i class="fa fa-minus"></i>
        </th>

        <th class="tooltipster" title="spent after starting date">
            <i class="fa fa-minus"></i>
        </th>

        <th class="tooltipster" title="received after starting date">
            <i class="fa fa-plus"></i>
        </th>

        <th class="tooltipster" title="remaining">R</th>

        <th>
            <i class="fa fa-times"></i>
        </th>
    </tr>
    <!-- table content -->
    <tr ng-repeat="budget in flexBudgets | orderBy: 'name'" class="budget_info_ul">
        <td ng-click="showBudgetPopup(budget, 'flex')" class="pointer">[[budget.name]]</td>
        <td ng-click="showBudgetPopup(budget, 'flex')" class="percent pointer">[[budget.amount]]</td>
        <td ng-click="showBudgetPopup(budget, 'flex')" class="amount pointer">[[budget.calculatedAmount | number:2]]</td>
        <td ng-click="showBudgetPopup(budget, 'flex')" class="CSD pointer">
            <span>[[budget.formattedStartingDate]]</span>
        </td>
        <td ng-click="showBudgetPopup(budget, 'flex')" class="month-number pointer">[[budget.cumulativeMonthNumber]]</td>

        <td ng-click="showBudgetPopup(budget, 'flex')" class="pointer">[[budget.spentBeforeStartingDate | number:2]]</td>

        <td ng-click="showBudgetPopup(budget, 'flex')" class="pointer">[[budget.spentAfterStartingDate | number:2]]</td>
        <td ng-click="showBudgetPopup(budget, 'flex')" class="received pointer">[[budget.receivedAfterStartingDate | number:2]]</td>
        <td ng-click="showBudgetPopup(budget, 'flex')" class="remaining pointer">[[budget.remaining | number:2]]</td>
        <td>
            <button ng-click="deleteBudget(budget)" class="btn btn-xs btn-danger">delete</button>
        </td>
    </tr>
    <!-- allocated -->
    <tr id="flex-budget-totals" class="budget_info_ul">
        <td>allocated</td>
        <td>[[flexBudgetTotals.allocatedAmount | number:2]]</td>
        <td>[[flexBudgetTotals.allocatedCalculatedAmount | number:2]]</td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
        <td>[[flexBudgetTotals.allocatedRemaining | number:2]]</td>
        <td>-</td>
    </tr>
    {{--unallocated--}}
    <tr id="flex-budget-unallocated" class="budget_info_ul">
        <td>unallocated</td>
        <td>[[flexBudgetTotals.unallocatedAmount]]</td>
        <td>[[flexBudgetTotals.unallocatedCalculatedAmount | number:2]]</td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
        <td>[[flexBudgetTotals.unallocatedRemaining | number:2]]</td>
        <td>-</td>
    </tr>
    <!-- flex budget totals -->
    <tr id="flex-budget-totals" class="budget_info_ul totals">
        <td>totals</td>
        <td>[[flexBudgetTotals.allocatedPlusUnallocatedAmount]]</td>
        <td>[[flexBudgetTotals.allocatedPlusUnallocatedCalculatedAmount | number:2]]</td>
        <td>-</td>
        <td>-</td>
        <td>[[flexBudgetTotals.spentBeforeStartingDate | number:2]]</td>
        <td>[[flexBudgetTotals.spentAfterStartingDate | number:2]]</td>
        <td>[[flexBudgetTotals.receivedAfterStartingDate | number:2]]</td>
        <td>[[flexBudgetTotals.allocatedPlusUnallocatedRemaining | number:2]]</td>
        <td>-</td>
    </tr>
</table>