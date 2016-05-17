<script id="home-page-template" type="x-template">

    <div>
        @include('main.home.toolbar')

        <new-transaction
            :show="show"
            :tab="tab"
            :budgets="budgets"
        >
        </new-transaction>

        <totals
            :show="show"
        >
        </totals>

        <transactions
            v-show="tab === 'transactions'"
            :show="show"
            :transaction-properties-to-show="transactionPropertiesToShow",
        >
        </transactions>

        <mass-transaction-update-popup
                :budgets="budgets"
        >
        </mass-transaction-update-popup>

        <graphs
            v-show="tab === 'graphs'"
        >
        </graphs>

        <filter
            :show="show"
            :tab="tab"
            :budgets="budgets"
        ></filter>
    </div>

</script>