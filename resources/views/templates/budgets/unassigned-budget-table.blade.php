
<h1>Unassigned Budget Table</h1>

<table id="unassigned-budget-info-table" class="table table-bordered">

    <tr>
        <th>Name</th>

        <th>
            <i class="fa fa-times"></i>
        </th>
    </tr>

    <!-- table content -->
    <tr ng-repeat="budget in unassignedBudgets | orderBy: 'name'" class="budget_info_ul">

        <td ng-click="showBudgetPopup(budget, 'unassigned')" class="pointer">[[budget.name]]</td>

        <td>
            <button ng-click="deleteBudget(budget)" class="btn btn-xs btn-danger">delete</button>
        </td>

    </tr>

</table>