<script id="totals-for-filter-template" type="x-template">

    <div>
        <div v-show="show.filter_totals" id="filter-totals" class="col-sm-2">
            <ul class="list-group">
                <li class="list-group-item list-group-heading">
                    Filter totals
                </li>

                <li class="tooltipster list-group-item list-group-item-success">
                    <span id="search_income_span" class="badge">@{{ filterTotals.credit | numberFilter 2 }}</span>
                    Credit:
                </li>

                <li class="tooltipster list-group-item list-group-item-danger">
                    <span id="search_expenses_span" class="badge">@{{ filterTotals.debit | numberFilter 2 }}</span>
                    Debit:
                </li>

                <li class="tooltipster list-group-item list-group-item-success">
                    <span id="search_income_span" class="badge">@{{ filterTotals.creditIncludingTransfers | numberFilter 2 }}</span>
                    Credit including transfers:
                </li>

                <li class="tooltipster list-group-item list-group-item-danger">
                    <span id="search_expenses_span" class="badge">@{{ filterTotals.debitIncludingTransfers | numberFilter 2 }}</span>
                    Debit including transfers:
                </li>

                <li class="tooltipster list-group-item list-group-item-warning" title="balance">
                    <span id="search_total_span" class="badge">@{{ filterTotals.balance | numberFilter 2 }}</span>
                    Balance:
                </li>

                <li class="tooltipster list-group-item list-group-item-info" title="reconciled">
                    <span id="asr_span" class="badge">@{{ filterTotals.reconciled | numberFilter 2 }}</span>
                    Sum of reconciled transactions:
                </li>

                <li class="tooltipster list-group-item list-group-item-info" title="# of search results">
                    <span id="search_results_span" class="badge">@{{ filter.displayFrom }} to @{{ filter.displayTo }} of @{{ filterTotals.numTransactions }}</span>
                    #:
                </li>
            </ul>
        </div>
    </div>

</script>