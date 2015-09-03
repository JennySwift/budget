
<h1>Flex Budget Table</h1>

<table id ="flex-budget-info-table" class="table table-bordered">

    <tr>
        <th class="tag">Tag</th>
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
    <tr ng-repeat="budget in flexBudgets" class="budget_info_ul">
        <td ng-click="showBudgetPopup(tag, 'flex')" class="budget-tag pointer">[[budget.name]]</td>
        <td ng-click="showBudgetPopup(tag, 'flex')" class="percent pointer">[[budget.amount]]</td>
        <td ng-click="showBudgetPopup(tag, 'flex')" class="amount pointer">[[budget.calculatedAmount]]</td>
        <td ng-click="showBudgetPopup(tag, 'flex')" class="CSD pointer">
            <span>[[budget.formattedStartingDate]]</span>
        </td>
        <td ng-click="showBudgetPopup(tag, 'flex')" class="month-number pointer">[[budget.cumulativeMonthNumber]]</td>

        <td ng-click="showBudgetPopup(tag, 'flex')" class="spent pointer">
            <div>[[budget.spentBeforeStartingDate]]</div>
        </td>

        <td ng-click="showBudgetPopup(tag, 'flex')" class="spent pointer">[[budget.spentAfterStartingDate]]</td>
        <td ng-click="showBudgetPopup(tag, 'flex')" class="received pointer">[[budget.receivedAfterStartingDate]]</td>
        <td ng-click="showBudgetPopup(tag, 'flex')" class="remaining pointer">[[budget.remaining]]</td>
        <td>
            <button ng-click="removeBudget(tag)" class="btn btn-xs btn-danger">delete</button>
        </td>
    </tr>
    <!-- allocated -->
    <tr id="flex-budget-totals" class="budget_info_ul">
        <td>allocated</td>
        <td>[[flexBudgetTotals.allocatedAmount]]</td>
        <td>[[flexBudgetTotals.allocatedCalculatedAmount]]</td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
        <td>[[flexBudgetTotals.allocatedRemaining]]</td>
        <td>-</td>
    </tr>
    {{--unallocated--}}
    <tr id="flex-budget-unallocated" class="budget_info_ul">
        <td>unallocated</td>
        <td>[[flexBudgetTotals.unallocatedAmount]]</td>
        <td>[[flexBudgetTotals.unallocatedCalculatedAmount]]</td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
        <td>[[flexBudgetTotals.unallocatedRemaining]]</td>
        <td>-</td>
    </tr>
    <!-- flex budget totals -->
    <tr id="flex-budget-totals" class="budget_info_ul totals">
        <td>totals</td>
        <td>[[flexBudgetTotals.allocatedPlusUnallocatedAmount]]</td>
        <td>[[flexBudgetTotals.allocatedPlusUnallocatedCalculatedAmount]]</td>
        <td>-</td>
        <td>-</td>
        <td>[[flexBudgetTotals.spentBeforeStartingDate]]</td>
        <td>[[flexBudgetTotals.spentAfterStartingDate]]</td>
        <td>[[flexBudgetTotals.receivedAfterStartingDate]]</td>
        <td>[[flexBudgetTotals.allocatedPlusUnallocatedRemaining]]</td>
        <td>-</td>
    </tr>
</table>