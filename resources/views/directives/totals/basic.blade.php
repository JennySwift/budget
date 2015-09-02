<div ng-show="show.basic_totals">

    <table class="totals">

        <tr>
            <th>Type</th>
            <th>Total</th>
            <th>Changed</th>
        </tr>

        <tr class="tooltipster" title="credit">
            <td>Credit:</td>
            <td><span class="badge badge-success">[[basicTotals.credit]]</span></td>
            <td><span ng-if="totalChanges.credit">[[totalChanges.credit]]</span></td>
        </tr>

        <tr class="tooltipster" title="remaining fixed budget (total of fixed budget info column R)">
            <td>Remaining fixed budget:</td>
            <td><span class="badge badge-danger">[[fixedBudgetTotals.remaining]]</span></td>
            <td><span ng-if="totalChanges.remainingFixedBudget">[[totalChanges.remainingFixedBudget]]</span></td>
        </tr>

        <tr class="tooltipster" title="total of expense transactions that have no budget">
            <td>Expenses with no budget:</td>
            <td><span class="badge badge-danger">[[basicTotals.EWB]]</span></td>
            <td><span ng-if="totalChanges.EWB">[[totalChanges.EWB]]</span></td>
        </tr>

        <tr class="tooltipster" title="total of allocation of tags of expense transactions that have a fixed budget before its starting date">
            <td>Expenses with <b>fixed</b> budget <b>before</b> starting date:</td>
            <td><span class="badge badge-danger">[[fixedBudgetTotals.spentBeforeStartingDate]]</span></td>
            <td><span ng-if="totalChanges.fixedBudgetExpensesBeforeStartingDate">[[totalChanges.fixedBudgetExpensesBeforeStartingDate]]</span></td>
        </tr>

        <tr class="tooltipster" title="total of allocation of tags of expense transactions that have a fixed budget after its starting date">
            <td>Expenses with <b>fixed</b> budget <b>after</b> starting date:</td>
            <td><span id="total_income_span" class="badge badge-danger">[[fixedBudgetTotals.spentAfterStartingDate]]</span></td>
            <td><span ng-if="totalChanges.fixedBudgetExpensesAfterStartingDate">[[totalChanges.fixedBudgetExpensesAfterStartingDate]]</span></td>
        </tr>

        <tr class="tooltipster" title="total of allocation of tags of expense transactions that have a flex budget before its starting date">
            <td>Expenses with <b>flex</b> budget <b>before</b> starting date:</td>
            <td><span class="badge badge-danger">[[flexBudgetTotals.spentBeforeStartingDate]]</span></td>
            <td><span ng-if="totalChanges.flexBudgetExpensesBeforeStartingDate">[[totalChanges.flexBudgetExpensesBeforeStartingDate]]</span></td>
        </tr>

        <tr class="tooltipster" title="savings">
            <td>Savings:</td>
            <td><span class="badge badge-danger">[[basicTotals.savings]]</span></td>
            <td><span ng-if="totalChanges.savings">[[totalChanges.savings]]</span></td>
        </tr>

        <tr class="tooltipster" title="remaining balance without EFLB">
            <td>Remaining balance:</td>
            <td><span class="badge badge-danger">[[remainingBalance]]</span></td>
            <td>
                <span ng-if="totalChanges.remainingBalance">[[totalChanges.remainingBalance]]</span>
            </td>
        </tr>

    </table>

</div>