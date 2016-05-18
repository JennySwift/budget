<script id="transactions-page-template" type="x-template">

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
            :show="show"
            :transaction-properties-to-show="transactionPropertiesToShow",
        >
        </transactions>

        <mass-transaction-update-popup
                :budgets="budgets"
        >
        </mass-transaction-update-popup>

        <filter
            :show="show"
            :budgets="budgets"
        ></filter>
    </div>

</script>