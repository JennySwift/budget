<!--<div>-->
<!--    <label>Choose a transaction type</label>-->
<!---->
<!--    <select-->
<!--        v-model="new_transaction.type"-->
<!--        name=""-->
<!--        v-on:keyup="insertTransaction($event.keyCode)"-->
<!--        id="select_transaction_type"-->
<!--        class="mousetrap form-control">-->
<!--        <option value="income">Credit</option>-->
<!--        <option value="expense">Debit</option>-->
<!--        <option value="transfer">Transfer</option>-->
<!--    </select>-->
<!--</div>-->

<div class="btn-group">
    <button v-on:click="new_transaction.type = 'income'" v-bind:style="{background: colors.income}" class="btn">Credit</button>
    <button v-on:click="new_transaction.type = 'expense'" v-bind:style="{background: colors.expense}" class="btn">Debit</button>
    <button v-on:click="new_transaction.type = 'transfer'" v-bind:style="{background: colors.transfer}" class="btn">Transfer</button>
</div>