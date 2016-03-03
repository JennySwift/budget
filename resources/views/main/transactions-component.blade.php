<script id="transactions-template" type="x-template">

<div>

    <h3 v-if="transactions.length === 0">No transactions to show.</h3>

    <edit-transaction-popup
        :transaction.sync="transactions"
    >
    </edit-transaction-popup>

    <table v-if="transactions.length > 0" id="transactions" class="">

        @include('main.home.transactions.table-header')

        <tbody
                is="transaction"
                :show-status="showStatus"
                :show-date="showDate"
                :show-description="showDescription"
                :show-merchant="showMerchant"
                :show-total="showTotal"
                :show-type="showType"
                :show-account="showAccount"
                :show-duration="showDuration"
                :show-reconciled="showReconciled"
                :show-allocated="showAllocated"
                :show-budgets="showBudgets"
                :show-delete="showDelete"
                v-for="transaction in transactions"
                :transaction="transaction"
                v-bind:style="{color: me.preferences.colors[transaction.type]}"
                :id="transaction.id"
                class="add_to_search_total results-transaction-tbody @{{ transaction.type }}">
        </tbody>
    </table>
</div>

</script>