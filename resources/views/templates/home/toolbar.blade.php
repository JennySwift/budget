<div id="toolbar">

    <div>

        @include('templates.home.show-button')
        @include('templates.home.actions-button')

        <button
            ng-if="!show.new_transaction"
            ng-click="show.new_transaction = !show.new_transaction"
            class="btn btn-info">
            New transaction
        </button>

        <button
            ng-if="show.new_transaction"
            ng-click="show.new_transaction = !show.new_transaction"
            class="btn btn-info">
            Hide new transaction
        </button>

    </div>

    <div class="btn-group">
        <button
                ng-click="transactionsTab()"
                ng-class="{'selected': tab === 'transactions'}"
                class="btn">
                Transactions
        </button>

        <button
                ng-click="graphsTab()"
                ng-class="{'selected': tab === 'graphs'}"
                class="btn">
                Graphs
        </button>
    </div>

    <div class="toolbar-filter">
        <filter-toolbar-directive></filter-toolbar-directive>

    </div>
    
</div>