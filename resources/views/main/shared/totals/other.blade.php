<div v-show="show.budgetTotals" class="totals">

    <table class="totals-table">

        <tr>
            <th>Type</th>
            <th>Total</th>
            <th>Changed</th>
        </tr>

        <tr class="tooltipster" title="debit">
            <td>Debit:</td>
            <td><span id="total_income_span" class="badge badge-danger">@{{ sideBarTotals.debit |  numberFilter 2 }}</span></td>
            <td><span v-if="totalChanges.debit">@{{ totalChanges.debit |  numberFilter 2 }}</span></td>
        </tr>

        <tr class="tooltipster" title="balance (C - D)">
            <td>Balance:</td>
            <td><span id="total_income_span" class="badge badge-warning">@{{ sideBarTotals.balance |  numberFilter 2 }}</span></td>
            <td><span v-if="totalChanges.balance">@{{ totalChanges.balance |  numberFilter 2 }}</span></td>
        </tr>

        <tr class="tooltipster" title="reconciled">
            <td>Reconciled:</td>
            <td><span id="total_income_span" class="badge badge-info">@{{ sideBarTotals.reconciledSum |  numberFilter 2 }}</span></td>
            <td><span v-if="totalChanges.reconciledSum">@{{ totalChanges.reconciledSum |  numberFilter 2 }}</span></td>
        </tr>

        <tr class="tooltipster" title="fixed budget (total of fixed budget info column C)">
            <td>Cumulative fixed budget:</td>
            <td><span id="total_income_span" class="badge badge-danger">@{{ sideBarTotals.cumulativeFixedBudget |  numberFilter 2 }}</span></td>
            <td><span v-if="totalChanges.cumulativeFixedBudget">@{{ totalChanges.cumulativeFixedBudget |  numberFilter 2 }}</span></td>
        </tr>

        <tr class="tooltipster" title="total of allocation of tags of expense transactions that have a flex budget">
            <td>Expenses with <b>flex</b> budget <b>after</b> starting date:</td>
            <td><span id="total_income_span" class="badge badge-danger">@{{ sideBarTotals.expensesWithFlexBudgetAfterStartingDate |  numberFilter 2 }}</span></td>
            <td><span v-if="totalChanges.expensesWithFlexBudgetAfterStartingDate">@{{ totalChanges.expensesWithFlexBudgetAfterStartingDate |  numberFilter 2 }}</span></td>
        </tr>

    </table>

</div>