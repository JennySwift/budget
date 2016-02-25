<div id="toolbar">

    <div>

        <button
            v-if="!show.new_transaction"
            v-on:click="show.new_transaction = !show.new_transaction"
            class="btn btn-info">
            New transaction
        </button>

        <button
            v-if="show.new_transaction"
            v-on:click="show.new_transaction = !show.new_transaction"
            class="btn btn-info">
            Hide new transaction
        </button>

    </div>

    <div class="btn-group">
        <button
                v-on:click="transactionsTab()"
                v-bind:class="{'selected': tab === 'transactions'}"
                class="btn">
                Transactions
        </button>

        <button
                v-on:click="graphsTab()"
                v-bind:class="{'selected': tab === 'graphs'}"
                class="btn">
                Graphs
        </button>
    </div>
    
</div>