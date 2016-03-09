<script id="home-page-template" type="x-template">

    <div>
        @include('main.home.toolbar')

        <new-transaction
            :show="show"
            :tab="tab"
            :transactions.sync="transactions"
            :budgets="budgets"
        >
        </new-transaction>

        <div class="main-content">

            <totals
                :show="show"
            >
            </totals>

            <div v-show="tab === 'transactions'" class="flex-grow-2">
                <transactions
                        :show="show"
                        :transaction-properties-to-show="transactionPropertiesToShow",
                        :transactions.sync="transactions"
                >
                </transactions>

                <mass-transaction-update-popup
                    :transactions.sync="transactions"
                    :budgets="budgets"
                >
                </mass-transaction-update-popup>
            </div>

            <div v-show="tab === 'graphs'" class="flex-grow-2">
                <graphs
                >
                </graphs>
            </div>

            <filter
                :show="show"
                :tab="tab"
                :budgets="budgets"
            ></filter>

        </div>
    </div>

</script>