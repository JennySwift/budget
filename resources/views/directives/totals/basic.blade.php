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
            <td><span ng-if="totals.changes.credit">[[totals.changes.credit]]</span></td>
        </tr>

        <tr class="tooltipster" title="remaining fixed budget (total of fixed budget info column R)">
            <td>Remaining fixed budget:</td>
            <td><span class="badge badge-danger">[[fixedBudgetTotals.remaining]]</span></td>
            <td><span ng-if="totals.changes.RFB">[[totals.changes.RFB]]</span></td>
        </tr>

        <tr class="tooltipster" title="total of expense transactions that have no budget">
            <td>Expenses with no budget:</td>
            <td><span class="badge badge-danger">[[basicTotals.EWB]]</span></td>
            <td><span ng-if="totals.changes.EWB">[[totals.changes.EWB]]</span></td>
        </tr>

        <tr class="tooltipster" title="total of allocation of tags of expense transactions that have a fixed budget before its starting date">
            <td>Expenses with <b>fixed</b> budget <b>before</b> starting date:</td>
            <td><span class="badge badge-danger">[[fixedBudgetTotals.spentBeforeStartingDate]]</span></td>
            <td><span ng-if="totals.changes.EFBBSD">[[totals.changes.EFBBSD]]</span></td>
        </tr>

        <tr class="tooltipster" title="total of allocation of tags of expense transactions that have a flex budget before its starting date">
            <td>Expenses with <b>flex</b> budget <b>before</b> starting date:</td>
            <td><span class="badge badge-danger">[[flexBudgetTotals.spentBeforeStartingDate]]</span></td>
            <td><span ng-if="totals.changes.EFLBBSD">[[totals.changes.EFLBBSD]]</span></td>
        </tr>

        <tr class="tooltipster" title="total of allocation of tags of expense transactions that have a flex budget">
            <td>Expenses with <b>flex</b> budget <b>after</b> starting date:</td>
            <td><span id="total_income_span" class="badge badge-danger">[[flexBudgetTotals.spentAfterStartingDate]]</span></td>
            <td><span ng-if="totals.changes.EFLB">[[totals.changes.EFLB]]</span></td>
        </tr>

        <tr class="tooltipster" title="total of allocation of tags of expense transactions that have a fixed budget after its starting date">
            <td>Expenses with <b>fixed</b> budget <b>after</b> starting date:</td>
            <td><span id="total_income_span" class="badge badge-danger">[[fixedBudgetTotals.spentAfterStartingDate]]</span></td>
            <td><span ng-if="totals.changes.EFBASD">[[totals.changes.EFBASD]]</span></td>
        </tr>

        <tr class="tooltipster" title="savings">
            <td>Savings:</td>
            <td><span class="badge badge-danger">[[basicTotals.savings]]</span></td>
            <td><span ng-if="totals.changes.savings">[[totals.changes.savings]]</span></td>
        </tr>

        <tr class="tooltipster" title="remaining balance without EFLB">
            <td>Remaining balance:</td>
            <td><span class="badge badge-danger">[[totals.budget.RBWEFLB]]</span></td>
            <td>
                <span ng-if="totals.changes.RBWEFLB">[[totals.changes.RBWEFLB]]</span>
            </td>
        </tr>

    </table>

    {{--<ul class="list-group totals">--}}

        {{--<li class="tooltipster list-group-item list-group-item-danger" title="remaining fixed budget (total of fixed budget info column R)">--}}
            {{--RFB:--}}
            {{--<span id="budget_span" class="badge">[[fixedBudgetTotals.remaining]]</span>--}}
            {{--<span ng-if="totals.changes.RFB" class="changed">Changed: [[totals.changes.RFB]]</span>--}}
        {{--</li>--}}

        {{--<li class="tooltipster list-group-item list-group-item-danger" title="total of expense transactions that have no budget">--}}
            {{--EWB:--}}
            {{--<span class="badge">[[basicTotals.EWB]]</span>--}}
            {{--<span ng-if="totals.changes.EWB" class="changed">Changed: [[totals.changes.EWB]]</span>--}}
        {{--</li>--}}

        {{--<li class="tooltipster list-group-item list-group-item-danger" title="total of allocation of tags of expense transactions that have a fixed budget before its starting date">--}}
            {{--EFBBSD:--}}
            {{--<span class="badge">[[fixedBudgetTotals.spentBeforeSD]]</span>--}}
            {{--<span ng-if="totals.changes.EFBBSD" class="changed">Changed: [[totals.changes.EFBBSD]]</span>--}}
        {{--</li>--}}

        {{--<li class="tooltipster list-group-item list-group-item-danger" title="total of allocation of tags of expense transactions that have a flex budget before its starting date">--}}
            {{--EFLBBSD:--}}
            {{--<span class="badge">[[totals.budget.FLB.totals.spentBeforeSD]]</span>--}}
            {{--<span ng-if="totals.changes.EFLBBSD" class="changed">Changed: [[totals.changes.EFLBBSD]]</span>--}}
        {{--</li>--}}

        {{--<li class="tooltipster list-group-item list-group-item-danger" title="savings">--}}
            {{--S:--}}
            {{--<span class="badge">[[basicTotals.savings]]</span>--}}
            {{--<button ng-show="show.savings_total.edit_btn" ng-click="showSavingsTotalInput()" class="btn-xs">edit</button>--}}
            {{--<input ng-show="show.savings_total.input" ng-model="basicTotals.savings_total" ng-keyup="updateSavingsTotal($event.keyCode)" type="text" placeholder="new savings total" id="edited-savings-total">--}}
            {{--<span ng-if="totals.changes.savings" class="changed">Changed: [[totals.changes.savings]]</span>--}}
        {{--</li>--}}

        {{--<li class="tooltipster list-group-item list-group-item-info" title="remaining balance without EFLB">--}}
            {{--RB:--}}
            {{--<span class="badge">[[totals.budget.RBWEFLB]]</span>--}}
            {{--<div ng-if="totals.changes.RBWEFLB.length > 0" class="changed">--}}
                {{--<span ng-repeat="change in totals.changes.RBWEFLB">Changed: [[change]]</span>--}}
            {{--</div>--}}
        {{--</li>--}}

    {{--</ul>--}}

</div>