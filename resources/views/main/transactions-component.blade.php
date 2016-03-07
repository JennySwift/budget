<script id="transactions-template" type="x-template">

<div>

    <h3 v-if="transactions.length === 0">No transactions to show.</h3>

    <edit-transaction-popup
        :transactions.sync="transactions"
        :accounts="accounts"
    >
    </edit-transaction-popup>

    <allocation-popup></allocation-popup>

    <table v-if="transactions.length > 0" id="transactions" class="">

        @include('main.home.transactions.table-header')

        <tbody
                v-for="transaction in transactions"
                is="transaction"
                :transaction-properties-to-show="transactionPropertiesToShow"
                :transactions.sync="transactions"
                :transaction="transaction"
                v-bind:style="{color: me.preferences.colors[transaction.type]}"
                :id="transaction.id"
                class="add_to_search_total results-transaction-tbody @{{ transaction.type }}">
        </tbody>
    </table>
</div>

</script>