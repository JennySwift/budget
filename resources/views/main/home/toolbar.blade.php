<div id="toolbar">

    <div>

        <button
            v-if="!show.newTransaction"
            v-on:click="toggleNewTransaction()"
            class="btn btn-info">
            New transaction
        </button>

        <button
            v-if="show.newTransaction"
            v-on:click="toggleNewTransaction()"
            class="btn btn-info">
            Hide new transaction
        </button>

    </div>

    <div class="btn-group">
        <button
                v-on:click="switchTab('transactions')"
                v-bind:class="{'selected': tab === 'transactions'}"
                class="btn">
                Transactions
        </button>

        <button
                v-on:click="switchTab('graphs')"
                v-bind:class="{'selected': tab === 'graphs'}"
                class="btn">
                Graphs
        </button>
    </div>
    
</div>