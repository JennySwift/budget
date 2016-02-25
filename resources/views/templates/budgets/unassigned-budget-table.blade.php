
<h1>Unassigned Budget Table</h1>

<table id="unassigned-budget-info-table" class="table table-bordered">

    <tr>
        <th>Name</th>

        <th>
            <i class="fa fa-times"></i>
        </th>
    </tr>

    <!-- table content -->
    <tr v-for="budget in unassignedBudgets | orderBy: 'name'" class="budget_info_ul">

        <td v-on:click="showBudgetPopup(budget, 'unassigned')" class="pointer">[[budget.name]]</td>

        <td>
            <button v-on:click="deleteBudget(budget)" class="btn btn-xs btn-danger">delete</button>
        </td>

    </tr>

</table>