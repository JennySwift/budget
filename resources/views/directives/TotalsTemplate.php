
<div ng-show="show.basic_totals || show.budget_totals" class="col-sm-2">
    <!-- basic totals -->
    <button ng-click="clearChanges()" class="btn btn-info btn-xs">clear changes</button>
    <div ng-show="show.basic_totals">

        <ul class="list-group totals">

            <li id="total_income" class="tooltipster list-group-item list-group-item-success" title="credit">
                <span id="total_income_span" class="badge">[[totals.basic.credit]]</span>
                C:
                <span ng-if="totals.changes.credit" class="changed">Changed: [[totals.changes.credit]]</span>
            </li>

            <li class="tooltipster list-group-item list-group-item-danger" title="remaining fixed budget (total of fixed budget info column R)">
                RFB:
                <span id="budget_span" class="badge">[[totals.budget.FB.totals.remaining]]</span>
                <span ng-if="totals.changes.RFB" class="changed">Changed: [[totals.changes.RFB]]</span>
            </li>

            <li class="tooltipster list-group-item list-group-item-danger" title="total of expense transactions that have no budget">
                EWB:
                <span class="badge">[[totals.basic.EWB]]</span>
                <span ng-if="totals.changes.EWB" class="changed">Changed: [[totals.changes.EWB]]</span>
            </li>

            <li class="tooltipster list-group-item list-group-item-danger" title="total of allocation of tags of expense transactions that have a fixed budget before its starting date">
                EFBBSD:
                <span class="badge">[[totals.budget.FB.totals.spentBeforeSD]]</span>
                <span ng-if="totals.changes.EFBBSD" class="changed">Changed: [[totals.changes.EFBBSD]]</span>
            </li>

            <li class="tooltipster list-group-item list-group-item-danger" title="total of allocation of tags of expense transactions that have a flex budget before its starting date">
                EFLBBSD:
                <span class="badge">[[totals.budget.FLB.totals.spentBeforeSD]]</span>
                <span ng-if="totals.changes.EFLBBSD" class="changed">Changed: [[totals.changes.EFLBBSD]]</span>
            </li>

            <li class="tooltipster list-group-item list-group-item-danger" title="savings">
                S:
                <span class="badge">[[totals.basic.savings]]</span>
                <button ng-show="show.savings_total.edit_btn" ng-click="showSavingsTotalInput()" class="btn-xs">edit</button>
                <input ng-show="show.savings_total.input" ng-model="totals.basic.savings_total" ng-keyup="updateSavingsTotal($event.keyCode)" type="text" placeholder="new savings total" id="edited-savings-total">
                <span ng-if="totals.changes.savings" class="changed">Changed: [[totals.changes.savings]]</span>
            </li>

            <!--            <li class="tooltipster list-group-item list-group-item-info" title="remaining balance (prev flexible income)">-->
            <!--                RB:-->
            <!--                <span class="badge">[[totals.budget.RB]]</span>-->
            <!--                <div ng-if="totals.changes.RB.length > 0" class="changed">-->
            <!--                    <span ng-repeat="change in totals.changes.RB">Changed: [[change]]</span>-->
            <!--                </div>-->
            <!--            </li>-->

            <li class="tooltipster list-group-item list-group-item-info" title="remaining balance without EFLB">
                <!--                RBWEFLB:-->
                RB:
                <span class="badge">[[totals.budget.RBWEFLB]]</span>
                <div ng-if="totals.changes.RBWEFLB.length > 0" class="changed">
                    <span ng-repeat="change in totals.changes.RBWEFLB">Changed: [[change]]</span>
                </div>
            </li>

        </ul>

    </div>

    <div ng-show="show.budget_totals">

        <ul class="list-group totals">


            <li id="total" class="tooltipster list-group-item list-group-item-danger" title="debit">
                <span class="badge">[[totals.basic.debit]]</span>
                <span ng-if="totals.changes.debit" class="changed">Changed: [[totals.changes.debit]]</span>
                D:
            </li>

            <li id="balance" class="tooltipster list-group-item list-group-item-warning" title="balance (C - D)">
                B:
                <span class="badge">[[totals.basic.balance]]</span>
                <span ng-if="totals.changes.balance" class="changed">Changed: [[totals.changes.balance]]</span>
            </li>

            <li class="tooltipster list-group-item list-group-item-info" title="reconciled">
                R:
                <span class="badge">[[totals.basic.reconciled_sum]]</span>
                <span ng-if="totals.changes.reconciled" class="changed">Changed: [[totals.changes.reconciled]]</span>
            </li>

            <li class="tooltipster list-group-item list-group-item-danger" title="fixed budget (total of fixed budget info column C)">
                CFB:
                <span id="budget_span" class="badge">[[totals.budget.FB.totals.cumulative]]</span>
                <span ng-if="totals.changes.CFB" class="changed">Changed: [[totals.changes.CFB]]</span>
            </li>

            <li class="tooltipster list-group-item list-group-item-danger" title="total of allocation of tags of expense transactions that have a flex budget">
                EFLBASD:
                <span class="badge">[[totals.budget.FLB.totals.spentAfterSD]]</span>
                <span ng-if="totals.changes.EFLB" class="changed">Changed: [[totals.changes.EFLB]]</span>
            </li>

            <li class="tooltipster list-group-item list-group-item-danger" title="total of allocation of tags of expense transactions that have a fixed budget after its starting date">
                EFBASD:
                <span class="badge">[[totals.budget.FB.totals.spentAfterSD]]</span>
                <span ng-if="totals.changes.EFBASD" class="changed">Changed: [[totals.changes.EFBASD]]</span>
            </li>

        </ul>

    </div>

</div>
