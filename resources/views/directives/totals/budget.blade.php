<div ng-show="show.budget_totals">

    <table class="totals">

        <tr>
            <th>Type</th>
            <th>Total</th>
            <th>Changed</th>
        </tr>

        <tr class="tooltipster" title="debit">
            <td>Debit:</td>
            <td><span id="total_income_span" class="badge badge-danger">[[basicTotals.debit]]</span></td>
            <td><span ng-if="totals.changes.debit">[[totals.changes.debit]]</span></td>
        </tr>

        <tr class="tooltipster" title="balance (C - D)">
            <td>Balance:</td>
            <td><span id="total_income_span" class="badge badge-warning">[[basicTotals.balance]]</span></td>
            <td><span ng-if="totals.changes.balance">[[totals.changes.balance]]</span></td>
        </tr>

        <tr class="tooltipster" title="reconciled">
            <td>Reconciled:</td>
            <td><span id="total_income_span" class="badge badge-info">[[basicTotals.reconciled_sum]]</span></td>
            <td><span ng-if="totals.changes.reconciled">[[totals.changes.reconciled]]</span></td>
        </tr>

        <tr class="tooltipster" title="fixed budget (total of fixed budget info column C)">
            <td>Cumulative fixed budget:</td>
            <td><span id="total_income_span" class="badge badge-danger">[[totals.budget.FB.totals.cumulative]]</span></td>
            <td><span ng-if="totals.changes.CFB">[[totals.changes.CFB]]</span></td>
        </tr>

    </table>

    {{--<ul class="list-group totals">--}}

        {{--<li id="total" class="tooltipster list-group-item list-group-item-danger" title="debit">--}}
            {{--<span class="badge">[[basicTotals.debit]]</span>--}}
            {{--<span ng-if="totals.changes.debit" class="changed">Changed: [[totals.changes.debit]]</span>--}}
            {{--D:--}}
        {{--</li>--}}

        {{--<li id="balance" class="tooltipster list-group-item list-group-item-warning" title="balance (C - D)">--}}
            {{--B:--}}
            {{--<span class="badge">[[basicTotals.balance]]</span>--}}
            {{--<span ng-if="totals.changes.balance" class="changed">Changed: [[totals.changes.balance]]</span>--}}
        {{--</li>--}}

        {{--<li class="tooltipster list-group-item list-group-item-info" title="reconciled">--}}
            {{--R:--}}
            {{--<span class="badge">[[basicTotals.reconciled_sum]]</span>--}}
            {{--<span ng-if="totals.changes.reconciled" class="changed">Changed: [[totals.changes.reconciled]]</span>--}}
        {{--</li>--}}

        {{--<li class="tooltipster list-group-item list-group-item-danger" title="fixed budget (total of fixed budget info column C)">--}}
            {{--CFB:--}}
            {{--<span id="budget_span" class="badge">[[totals.budget.FB.totals.cumulative]]</span>--}}
            {{--<span ng-if="totals.changes.CFB" class="changed">Changed: [[totals.changes.CFB]]</span>--}}
        {{--</li>--}}

        {{--<li class="tooltipster list-group-item list-group-item-danger" title="total of allocation of tags of expense transactions that have a flex budget">--}}
            {{--EFLBASD:--}}
            {{--<span class="badge">[[totals.budget.FLB.totals.spentAfterSD]]</span>--}}
            {{--<span ng-if="totals.changes.EFLB" class="changed">Changed: [[totals.changes.EFLB]]</span>--}}
        {{--</li>--}}

        {{--<li class="tooltipster list-group-item list-group-item-danger" title="total of allocation of tags of expense transactions that have a fixed budget after its starting date">--}}
            {{--EFBASD:--}}
            {{--<span class="badge">[[totals.budget.FB.totals.spentAfterSD]]</span>--}}
            {{--<span ng-if="totals.changes.EFBASD" class="changed">Changed: [[totals.changes.EFBASD]]</span>--}}
        {{--</li>--}}

    {{--</ul>--}}

</div>