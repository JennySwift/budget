<div ng-show="show.budget_totals">

    <table class="totals">

        <tr>
            <th>Type</th>
            <th>Total</th>
            <th>Changed</th>
        </tr>

        <tr class="tooltipster" title="debit">
            <td>Debit:</td>
            <td><span id="total_income_span" class="badge badge-danger">[[basicTotals.debit | number:2]]</span></td>
            <td><span ng-if="totalChanges.debit">[[totalChanges.debit | number:2]]</span></td>
        </tr>

        <tr class="tooltipster" title="balance (C - D)">
            <td>Balance:</td>
            <td><span id="total_income_span" class="badge badge-warning">[[basicTotals.balance | number:2]]</span></td>
            <td><span ng-if="totalChanges.balance">[[totalChanges.balance | number:2]]</span></td>
        </tr>

        <tr class="tooltipster" title="reconciled">
            <td>Reconciled:</td>
            <td><span id="total_income_span" class="badge badge-info">[[basicTotals.reconciledSum | number:2]]</span></td>
            <td><span ng-if="totalChanges.reconciledSum">[[totalChanges.reconciledSum | number:2]]</span></td>
        </tr>

        <tr class="tooltipster" title="fixed budget (total of fixed budget info column C)">
            <td>Cumulative fixed budget:</td>
            <td><span id="total_income_span" class="badge badge-danger">[[fixedBudgetTotals.cumulative | number:2]]</span></td>
            <td><span ng-if="totalChanges.cumalativeFixedBudget">[[totalChanges.cumulativeFixedBudget | number:2]]</span></td>
        </tr>

        <tr class="tooltipster" title="total of allocation of tags of expense transactions that have a flex budget">
            <td>Expenses with <b>flex</b> budget <b>after</b> starting date:</td>
            <td><span id="total_income_span" class="badge badge-danger">[[flexBudgetTotals.spentAfterStartingDate | number:2]]</span></td>
            <td><span ng-if="totalChanges.flexBudgetExpensesAfterStartingDate">[[totalChanges.flexBudgetExpensesAfterStartingDate | number:2]]</span></td>
        </tr>

    </table>

    {{--<ul class="list-group totals">--}}

        {{--<li id="total" class="tooltipster list-group-item list-group-item-danger" title="debit">--}}
            {{--<span class="badge">[[basicTotals.debit]]</span>--}}
            {{--<span ng-if="totalChanges.debit" class="changed">Changed: [[totalChanges.debit]]</span>--}}
            {{--D:--}}
        {{--</li>--}}

        {{--<li id="balance" class="tooltipster list-group-item list-group-item-warning" title="balance (C - D)">--}}
            {{--B:--}}
            {{--<span class="badge">[[basicTotals.balance]]</span>--}}
            {{--<span ng-if="totalChanges.balance" class="changed">Changed: [[totalChanges.balance]]</span>--}}
        {{--</li>--}}

        {{--<li class="tooltipster list-group-item list-group-item-info" title="reconciled">--}}
            {{--R:--}}
            {{--<span class="badge">[[basicTotals.reconciled_sum]]</span>--}}
            {{--<span ng-if="totalChanges.reconciled" class="changed">Changed: [[totalChanges.reconciled]]</span>--}}
        {{--</li>--}}

        {{--<li class="tooltipster list-group-item list-group-item-danger" title="fixed budget (total of fixed budget info column C)">--}}
            {{--CFB:--}}
            {{--<span id="budget_span" class="badge">[[fixedBudgetTotals.cumulative]]</span>--}}
            {{--<span ng-if="totalChanges.CFB" class="changed">Changed: [[totalChanges.CFB]]</span>--}}
        {{--</li>--}}

        {{--<li class="tooltipster list-group-item list-group-item-danger" title="total of allocation of tags of expense transactions that have a flex budget">--}}
            {{--EFLBASD:--}}
            {{--<span class="badge">[[totals.budget.FLB.totals.spentAfterSD]]</span>--}}
            {{--<span ng-if="totalChanges.EFLB" class="changed">Changed: [[totalChanges.EFLB]]</span>--}}
        {{--</li>--}}

        {{--<li class="tooltipster list-group-item list-group-item-danger" title="total of allocation of tags of expense transactions that have a fixed budget after its starting date">--}}
            {{--EFBASD:--}}
            {{--<span class="badge">[[fixedBudgetTotals.spentAfterSD]]</span>--}}
            {{--<span ng-if="totalChanges.EFBASD" class="changed">Changed: [[totalChanges.EFBASD]]</span>--}}
        {{--</li>--}}

    {{--</ul>--}}

</div>