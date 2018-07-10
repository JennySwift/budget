<template>
    <div>
        <div v-show="shared.show.filterTotals" id="filter-totals">
            <ul class="list-group">
                <li class="list-group-item list-group-heading">
                    Filter totals
                </li>

                <li class="tooltipster list-group-item list-group-item-success">
                    <span id="search_income_span" class="badge">{{ shared.filterTotals.credit | numberFilter(2) }}</span>
                    Credit:
                </li>

                <li class="tooltipster list-group-item list-group-item-danger">
                    <span id="search_expenses_span" class="badge">{{ shared.filterTotals.debit | numberFilter(2) }}</span>
                    Debit:
                </li>

                <li class="tooltipster list-group-item list-group-item-success">
                    <span id="search_income_span" class="badge">{{ shared.filterTotals.creditIncludingTransfers | numberFilter(2) }}</span>
                    Credit including transfers:
                </li>

                <li class="tooltipster list-group-item list-group-item-danger">
                    <span id="search_expenses_span" class="badge">{{ shared.filterTotals.debitIncludingTransfers | numberFilter(2) }}</span>
                    Debit including transfers:
                </li>

                <li class="tooltipster list-group-item list-group-item-warning" title="balance">
                    <span id="search_total_span" class="badge">{{ shared.filterTotals.balance | numberFilter(2) }}</span>
                    Balance:
                </li>

                <li class="tooltipster list-group-item list-group-item-warning" title="balance">
                    <span id="search_total_span" class="badge">{{ shared.filterTotals.balanceFromBeginning | numberFilter(2) }}</span>
                    Balance from beginning:
                </li>

                <li class="tooltipster list-group-item list-group-item-info" title="reconciled">
                    <span id="asr_span" class="badge">{{ shared.filterTotals.reconciled | numberFilter(2) }}</span>
                    Sum of reconciled transactions:
                </li>

                <li class="tooltipster list-group-item list-group-item-info" title="# of search results">
                    <span id="search_results_span" class="badge">{{ shared.filter.displayFrom }} to {{ shared.filter.displayTo }} of {{ shared.filterTotals.numTransactions }}</span>
                    #:
                </li>
            </ul>
        </div>
    </div>

</template>

<script>
    import helpers from '../../repositories/Helpers'
    export default {
        data: function () {
            return {
                shared: store.state
            };
        },
        components: {},
        filters: {
            numberFilter: function (number, howManyDecimals) {
                return helpers.numberFilter(number, howManyDecimals);
            }
        },
        methods: {

        },
        props: [
            'filterTotals'
        ],
        mounted: function () {

        }
    }
</script>