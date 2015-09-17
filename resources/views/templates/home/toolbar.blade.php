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

    <div ng-controller="FilterController" class="toolbar-filter">

        <div class="flex">
            <select ng-model="filter.num_to_fetch" name="" id="" class="form-control">
                <option value="2">2</option>
                <option value="4">4</option>
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="30">30</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>

            <span class="badge">[[filter.display_from]] to [[filter.display_to]] of [[filterTotals.numTransactions]]</span>

        </div>

        <div>
            <button
                ng-click="prevResults()"
                ng-disabled="filter.display_from <= 1"
                type="button"
                id="prev-results-button"
                class="navigate-results-button btn btn-info">
                Prev
            </button>

            <button
                ng-click="nextResults()"
                ng-disabled="filter.display_to >= filterTotals.numTransactions"
                type="button"
                id="next-results-button"
                class="navigate-results-button btn btn-info">
                Next
            </button>

            <button
                ng-click="resetFilter()"
                id="reset-search"
                class="btn btn-info">
                Reset Filter
            </button>
        </div>

    </div>
    
</div>