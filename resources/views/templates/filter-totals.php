<div ng-show="show.filter_totals" class="col-sm-2">
    <ul class="list-group totals">
        <li class="list-group-item list-group-heading">
            Search totals
        </li>
    
        <li class="tooltipster list-group-item list-group-item-success" title="credit">
            <span id="search_income_span" class="badge">[[filterTotals.income | number:2]]</span>
            C:  
        </li>
    
        <li class="tooltipster list-group-item list-group-item-danger" title="debit">
            <span id="search_expenses_span" class="badge">[[filterTotals.expenses | number:2]]</span>
            D:
        </li>
    
        <li class="tooltipster list-group-item list-group-item-warning" title="balance">
            <span id="search_total_span" class="badge">[[filterTotals.balance | number:2]]</span>
            B:
        </li>
    
        <li class="tooltipster list-group-item list-group-item-info" title="reconciled">
            <span id="asr_span" class="badge">[[filterTotals.reconciled | number:2]]</span>
            R:
        </li>
        
        <li class="tooltipster list-group-item list-group-item-info" title="# of search results">
            <span id="search_results_span" class="badge">[[filter.display_from]] to [[filter.display_to]] of [[filterTotals.numTransactions]]</span>
            #:
        </li>
    </ul>
</div>