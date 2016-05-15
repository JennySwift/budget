<div v-show="show.basicTotals" id="remaining-balance-totals" class="totals">

    <i v-if="totalsLoading" class="fa fa-spinner fa-pulse"></i>

    <table class="totals-table">

        <tr>
            <th>Type</th>
            <th>Total</th>
            <th>Changed</th>
        </tr>

        <tr class="tooltipster" title="credit">
            <td>Credit:</td>
            <td><span class="badge badge-success">@{{ sideBarTotals.credit | numberFilter 2 }}</span></td>
            <td><span v-if="totalChanges.credit">@{{ totalChanges.credit | numberFilter 2 }}</span></td>
        </tr>

        <tr class="tooltipster" title="remaining fixed budget (total of fixed budget info column R)">
            <td>Remaining fixed budget:</td>
            <td><span class="badge badge-danger">@{{ sideBarTotals.remainingFixedBudget | numberFilter 2 }}</span></td>
            <td><span v-if="totalChanges.remainingFixedBudget">@{{ totalChanges.remainingFixedBudget | numberFilter 2 }}</span></td>
        </tr>

        <tr class="tooltipster" title="total of expense transactions that have no budget">
            <td>Expenses with no fixed or flex budgets:</td>
            <td><span class="badge badge-danger">@{{ sideBarTotals.expensesWithoutBudget | numberFilter 2 }}</span></td>
            <td><span v-if="totalChanges.expensesWithoutBudget">@{{ totalChanges.expensesWithoutBudget | numberFilter 2 }}</span></td>
        </tr>

        <tr class="tooltipster" title="total of allocation of tags of expense transactions that have a fixed budget before its starting date">
            <td>Expenses with <b>fixed</b> budget <b>before</b> starting date:</td>
            <td><span class="badge badge-danger">@{{ sideBarTotals.expensesWithFixedBudgetBeforeStartingDate | numberFilter 2 }}</span></td>
            <td><span v-if="totalChanges.expensesWithFixedBudgetBeforeStartingDate">@{{ totalChanges.expensesWithFixedBudgetBeforeStartingDate | numberFilter 2 }}</span></td>
        </tr>

        <tr class="tooltipster" title="total of allocation of tags of expense transactions that have a fixed budget after its starting date">
            <td>Expenses with <b>fixed</b> budget <b>after</b> starting date:</td>
            <td><span id="total_income_span" class="badge badge-danger">@{{ sideBarTotals.expensesWithFixedBudgetAfterStartingDate | numberFilter 2 }}</span></td>
            <td><span v-if="totalChanges.expensesWithFixedBudgetAfterStartingDate">@{{ totalChanges.expensesWithFixedBudgetAfterStartingDate | numberFilter 2 }}</span></td>
        </tr>

        <tr class="tooltipster" title="total of allocation of tags of expense transactions that have a flex budget before its starting date">
            <td>Expenses with <b>flex</b> budget <b>before</b> starting date:</td>
            <td><span class="badge badge-danger">@{{ sideBarTotals.expensesWithFlexBudgetBeforeStartingDate | numberFilter 2 }}</span></td>
            <td><span v-if="totalChanges.expensesWithFlexBudgetBeforeStartingDate">@{{ totalChanges.expensesWithFlexBudgetBeforeStartingDate | numberFilter 2 }}</span></td>
        </tr>

        <tr class="tooltipster" title="savings">
            <td>Savings:</td>
            <td><span class="badge badge-danger">@{{ sideBarTotals.savings | numberFilter 2 }}</span></td>
            <td><span v-if="totalChanges.savings">@{{ totalChanges.savings | numberFilter 2 }}</span></td>
        </tr>

        <tr class="tooltipster" title="remaining balance without EFLB">
            <td>Remaining balance:</td>
            <td><span class="badge badge-danger">@{{ sideBarTotals.remainingBalance | numberFilter 2 }}</span></td>
            <td>
                <span v-if="totalChanges.remainingBalance">@{{ totalChanges.remainingBalance | numberFilter 2 }}</span>
            </td>
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