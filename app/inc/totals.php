
<div ng-show="show.basic_totals || show.budget_totals" class="col-sm-2">
    <!-- basic totals -->
    <div ng-show="show.basic_totals">

        <ul class="list-group">
            <li id="total_income" class="tooltipster list-group-item list-group-item-success" title="credit">
                <span id="total_income_span" class="badge">{{totals.basic.total_income}}</span>
                C:
            </li>

            <li class="tooltipster list-group-item list-group-item-danger" title="fixed budget (total of fixed budget info column A)">
                FB:
                <span id="budget_span" class="badge">{{totals.budget.FB.totals.budget}}</span>
            </li>

            <li class="tooltipster list-group-item list-group-item-danger" title="total of expense transactions that have no budget">
                EWB:
                <span id="flexible_income_span" class="badge">{{totals.basic.expense_without_budget_total}}</span>
            </li>

            <li class="tooltipster list-group-item list-group-item-danger" title="savings">
                S:
                <span class="badge">{{totals.basic.savings_total}}</span>
                <button ng-show="show.savings_total.edit_btn" ng-click="showSavingsTotalInput()" class="btn-xs">edit</button>
                <input ng-show="show.savings_total.input" ng-model="totals.basic.savings_total" ng-keyup="updateSavingsTotal($event.keyCode)" type="text" placeholder="new savings total">
            </li>

            <!-- <li class="tooltipster list-group-item" title="savings balance (balance - savings)">
                SB:
                <span class="badge">{{totals.basic.savings_balance}}</span>
            </li> -->

            <li class="tooltipster list-group-item" title="remaining balance (prev flexible income)">
                RB:
                <span id="flexible_income_span" class="badge">{{totals.budget.RB}}</span>
            </li>
        </ul>



        <ul class="list-group">
        
        
            <li id="total" class="tooltipster list-group-item list-group-item-danger" title="debit">
                <span id="total_expenses_span" class="badge">{{totals.basic.total_expense}}</span>
                D: 
            </li>

            <li id="balance" class="tooltipster list-group-item list-group-item-warning" title="balance (C - D)"> 
                B:
                <span id="balance_span" class="badge">{{totals.basic.balance}}</span>
            </li>
        
            <li class="tooltipster list-group-item list-group-item-info" title="reconciled">
                R:
                <span id="reconciled_span" class="badge">{{totals.basic.reconciled_sum}}</span>
            </li>
        
        </ul>
    </div>
    
    <!-- budget totals -->
    <div ng-show="show.budget_totals">
        <ul class="list-group">
        
            <li id="" class="tooltipster list-group-item" title="cumulative fixed budget (total of fixed budget info column C)">
                C/F/B:
                <span id="cumulative_fixed_budget_span" class="badge">{{totals.budget.FB.other.CFB}}</span>
            </li>
        
            <!-- <li class="tooltipster list-group-item list-group-item" title="cumulative month number">
                CMN:
                <span id="cumulative_month_number_span" class="badge">{{totals.CMN}}</span>
            </li> -->
        
            <li id="remaining_fixed_budget" class="tooltipster list-group-item" title="remaining fixed budget (total of fixed budget info column R)">
                R/F/B:
                <span id="remaining_fixed_budget_span" class="badge">{{totals.budget.FB.other.RFB}}</span>
            </li>
        
            <li class="tooltipster list-group-item" title="Spent Flex Budget-total of Flex Budget Info column S)">
                S/F/B:
                <span id="spent_flex_budget_span" class="badge">{{totals.budget.FLB.other.SFB}}</span>
            </li>
        
            <li class="tooltipster list-group-item" title="remaining flexible income (F/I - S/F/B)">
                R/F/I:
                <span id="remaining_flex_income_span" class="badge">{{totals.budget.FLB.other.RFI}}</span>
            </li>
        
        </ul>
    </div>
</div>
