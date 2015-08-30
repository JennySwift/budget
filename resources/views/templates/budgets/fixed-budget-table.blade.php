
<h1>Fixed Budget Table</h1>

<table id="fixed-budget-info-table" class="table table-bordered">

    <tr>
        <th class="tag">Name</th>
        <th class="tooltipster" title="amount">A</th>
        <th class="tooltipster" title="cumulative starting date">SD</th>
        <th class="tooltipster" title="cumulative month number">CMN</th>
        <th class="tooltipster" title="cumulative (amount * cumulative month number)">C</th>

        <th class="tooltipster" title="spent before cumulative starting date">
            <i class="fa fa-minus"></i>
        </th>

        <th class="tooltipster" title="spent since cumulative starting date">
            <i class="fa fa-minus"></i>
        </th>

        <th class="tooltipster" title="received since cumulative starting date">
            <i class="fa fa-plus"></i>
        </th>

        <th class="tooltipster" title="remaining  (cumulative + spent + received)">R</th>

        <th>
            <i class="fa fa-times"></i>
        </th>
    </tr>

    <!-- table content -->
    <tr ng-repeat="budget in fixedBudgets" class="budget_info_ul">

        <td ng-click="showBudgetPopup(tag, 'fixed')" class="budget-tag pointer">[[budget.name]]</td>

        <td ng-click="showBudgetPopup(tag, 'fixed')" class="amount right pointer">[[budget.amount]]</td>

        <td ng-click="showBudgetPopup(tag, 'fixed')" class="CSD pointer">
            <span>[[budget.formattedStartingDate]]</span>
        </td>

        <td ng-click="showBudgetPopup(tag, 'fixed')" class="month-number pointer">[[budget.cumulativeMonthNumber]]</td>

        <td ng-click="showBudgetPopup(tag, 'fixed')" class="cumulative pointer">[[budget.cumulative]]</td>

        <td ng-click="showBudgetPopup(tag, 'fixed')" class="spent pointer">
            <div>[[budget.spentBeforeStartingDate]]</div>
        </td>

        <td ng-click="showBudgetPopup(tag, 'fixed')" class="spent pointer">
            <div>[[budget.spentAfterStartingDate]]</div>
        </td>

        <td ng-click="showBudgetPopup(tag, 'fixed')" class="received pointer">[[budget.receivedAfterStartingDate]]</td>

        <td ng-click="showBudgetPopup(tag, 'fixed')" class="remaining pointer">[[budget.remaining]]</td>

        <td>
            <button ng-click="removeBudget(tag)" class="btn btn-xs btn-danger">delete</button>
        </td>

    </tr>

    <!-- fixed budget totals -->
    <tr id="fixed-budget-totals" class="budget_info_ul totals">
        <td>totals</td>
        <td>[[fixedBudgetTotals.amount]]</td>
        <td>-</td>
        <td>-</td>
        <td>[[fixedBudgetTotals.cumulative]]</td>
        <td>[[fixedBudgetTotals.spentBeforeStartingDate]]</td>
        <td>[[fixedBudgetTotals.spentAfterStartingDate]]</td>
        <td>[[fixedBudgetTotals.receivedAfterStartingDate]]</td>
        <td>[[fixedBudgetTotals.remaining]]</td>
        <td>-</td>
    </tr>

</table>