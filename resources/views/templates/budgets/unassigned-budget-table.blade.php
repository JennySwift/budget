
<h1>Unassigned Budget Table</h1>

<table id="unassigned-budget-info-table" class="table table-bordered">

    <tr>
        <th>Name</th>
        <th>Spent</th>
        <th>Received</th>

        <th>
            <i class="fa fa-times"></i>
        </th>
    </tr>

    <!-- table content -->
    <tr ng-repeat="budget in unassignedBudgets | orderBy: 'name'" class="budget_info_ul">

        <td ng-click="showBudgetPopup(budget, 'unassigned')" class="pointer">[[budget.name]]</td>
        <td ng-click="showBudgetPopup(budget, 'unassigned')" class="pointer">[[budget.spent | number:2]]</td>
        <td ng-click="showBudgetPopup(budget, 'unassigned')" class="pointer">[[budget.received | number:2]]</td>

        <td>
            <button ng-click="deleteBudget(budget)" class="btn btn-xs btn-danger">delete</button>
        </td>

    </tr>

    <!-- unassigned budget totals -->
    <tr id="unassigned-budget-totals" class="budget_info_ul totals">
        <td>totals</td>
        <td>[[unassignedBudgetTotals.spent | number:2]]</td>
        <td>[[unassignedBudgetTotals.received | number:2]]</td>
        <td>-</td>
    </tr>

</table>