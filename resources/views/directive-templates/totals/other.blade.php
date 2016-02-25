<div v-show="show.budget_totals">

    <table class="totals">

        <tr>
            <th>Type</th>
            <th>Total</th>
            <th>Changed</th>
        </tr>

        <tr class="tooltipster" title="debit">
            <td>Debit:</td>
            <td><span id="total_income_span" class="badge badge-danger">[[sideBarTotals.debit | number:2]]</span></td>
            <td><span v-if="totalChanges.debit">[[totalChanges.debit | number:2]]</span></td>
        </tr>

        <tr class="tooltipster" title="balance (C - D)">
            <td>Balance:</td>
            <td><span id="total_income_span" class="badge badge-warning">[[sideBarTotals.balance | number:2]]</span></td>
            <td><span v-if="totalChanges.balance">[[totalChanges.balance | number:2]]</span></td>
        </tr>

        <tr class="tooltipster" title="reconciled">
            <td>Reconciled:</td>
            <td><span id="total_income_span" class="badge badge-info">[[sideBarTotals.reconciledSum | number:2]]</span></td>
            <td><span v-if="totalChanges.reconciledSum">[[totalChanges.reconciledSum | number:2]]</span></td>
        </tr>

        <tr class="tooltipster" title="fixed budget (total of fixed budget info column C)">
            <td>Cumulative fixed budget:</td>
            <td><span id="total_income_span" class="badge badge-danger">[[sideBarTotals.cumulativeFixedBudget | number:2]]</span></td>
            <td><span v-if="totalChanges.cumulativeFixedBudget">[[totalChanges.cumulativeFixedBudget | number:2]]</span></td>
        </tr>

        <tr class="tooltipster" title="total of allocation of tags of expense transactions that have a flex budget">
            <td>Expenses with <b>flex</b> budget <b>after</b> starting date:</td>
            <td><span id="total_income_span" class="badge badge-danger">[[sideBarTotals.expensesWithFlexBudgetAfterStartingDate | number:2]]</span></td>
            <td><span v-if="totalChanges.expensesWithFlexBudgetAfterStartingDate">[[totalChanges.expensesWithFlexBudgetAfterStartingDate | number:2]]</span></td>
        </tr>

    </table>

</div>