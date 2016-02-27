<div v-show="showBasicTotals" id="remaining-balance-totals">

    <i v-if="totalsLoading" class="fa fa-spinner fa-pulse"></i>

    <table class="totals">

        <tr>
            <th>Type</th>
            <th>Total</th>
            <th>Changed</th>
        </tr>

        <tr class="tooltipster" title="credit">
            <td>Credit:</td>
            <td><span class="badge badge-success">@{{ sideBarTotals.credit | number:2 }}</span></td>
            <td><span v-if="totalChanges.credit">@{{ totalChanges.credit | number:2 }}</span></td>
        </tr>

        <tr class="tooltipster" title="remaining fixed budget (total of fixed budget info column R)">
            <td>Remaining fixed budget:</td>
            <td><span class="badge badge-danger">@{{ sideBarTotals.remainingFixedBudget | number:2 }}</span></td>
            <td><span v-if="totalChanges.remainingFixedBudget">@{{ totalChanges.remainingFixedBudget | number:2 }}</span></td>
        </tr>

        <tr class="tooltipster" title="total of expense transactions that have no budget">
            <td>Expenses with no fixed or flex budgets:</td>
            <td><span class="badge badge-danger">@{{ sideBarTotals.expensesWithoutBudget | number:2 }}</span></td>
            <td><span v-if="totalChanges.expensesWithoutBudget">@{{ totalChanges.expensesWithoutBudget | number:2 }}</span></td>
        </tr>

        <tr class="tooltipster" title="total of allocation of tags of expense transactions that have a fixed budget before its starting date">
            <td>Expenses with <b>fixed</b> budget <b>before</b> starting date:</td>
            <td><span class="badge badge-danger">@{{ sideBarTotals.expensesWithFixedBudgetBeforeStartingDate | number:2 }}</span></td>
            <td><span v-if="totalChanges.expensesWithFixedBudgetBeforeStartingDate">@{{ totalChanges.expensesWithFixedBudgetBeforeStartingDate | number:2 }}</span></td>
        </tr>

        <tr class="tooltipster" title="total of allocation of tags of expense transactions that have a fixed budget after its starting date">
            <td>Expenses with <b>fixed</b> budget <b>after</b> starting date:</td>
            <td><span id="total_income_span" class="badge badge-danger">@{{ sideBarTotals.expensesWithFixedBudgetAfterStartingDate | number:2 }}</span></td>
            <td><span v-if="totalChanges.expensesWithFixedBudgetAfterStartingDate">@{{ totalChanges.expensesWithFixedBudgetAfterStartingDate | number:2 }}</span></td>
        </tr>

        <tr class="tooltipster" title="total of allocation of tags of expense transactions that have a flex budget before its starting date">
            <td>Expenses with <b>flex</b> budget <b>before</b> starting date:</td>
            <td><span class="badge badge-danger">@{{ sideBarTotals.expensesWithFlexBudgetBeforeStartingDate | number:2 }}</span></td>
            <td><span v-if="totalChanges.expensesWithFlexBudgetBeforeStartingDate">@{{ totalChanges.expensesWithFlexBudgetBeforeStartingDate | number:2 }}</span></td>
        </tr>

        <tr class="tooltipster" title="savings">
            <td>Savings:</td>
            <td><span class="badge badge-danger">@{{ sideBarTotals.savings | number:2 }}</span></td>
            <td><span v-if="totalChanges.savings">@{{ totalChanges.savings | number:2 }}</span></td>
        </tr>

        <tr class="tooltipster" title="remaining balance without EFLB">
            <td>Remaining balance:</td>
            <td><span class="badge badge-danger">@{{ sideBarTotals.remainingBalance | number:2 }}</span></td>
            <td>
                <span v-if="totalChanges.remainingBalance">@{{ totalChanges.remainingBalance | number:2 }}</span>
            </td>
        </tr>

    </table>

</div>