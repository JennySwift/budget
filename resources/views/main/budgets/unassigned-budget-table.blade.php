
<h1>Unassigned Budget Table</h1>

<table id="unassigned-budget-info-table" class="table table-bordered">

    <tr>
        <th>Name</th>
    </tr>

    <!-- table content -->
    <tr v-for="budget in unassignedBudgets | orderBy 'name'" class="budget_info_ul">
        <td v-on:click="showBudgetPopup(budget, 'unassigned')" class="pointer">@{{ budget.name }}</td>
    </tr>

</table>