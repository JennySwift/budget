<!--<div>-->
<!--    <label>Choose a transaction type</label>-->
<!---->
<!--    <select-->
<!--        ng-model="new_transaction.type"-->
<!--        name=""-->
<!--        ng-keyup="insertTransaction($event.keyCode)"-->
<!--        id="select_transaction_type"-->
<!--        class="mousetrap form-control">-->
<!--        <option value="income">Credit</option>-->
<!--        <option value="expense">Debit</option>-->
<!--        <option value="transfer">Transfer</option>-->
<!--    </select>-->
<!--</div>-->

<div class="btn-group">
    <button ng-click="new_transaction.type = 'income'" ng-style="{background: colors.income}" class="btn">Credit</button>
    <button ng-click="new_transaction.type = 'expense'" ng-style="{background: colors.expense}" class="btn">Debit</button>
    <button ng-click="new_transaction.type = 'transfer'" ng-style="{background: colors.transfer}" class="btn">Transfer</button>
</div>