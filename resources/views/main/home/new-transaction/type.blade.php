<div class="btn-group">
    <button v-on:click="newTransaction.type = 'income'" v-bind:style="{background: me.preferences.colors.income}" class="btn">Credit</button>
    <button v-on:click="newTransaction.type = 'expense'" v-bind:style="{background: me.preferences.colors.expense}" class="btn">Debit</button>
    <button v-on:click="newTransaction.type = 'transfer'" v-bind:style="{background: me.preferences.colors.transfer}" class="btn">Transfer</button>
</div>