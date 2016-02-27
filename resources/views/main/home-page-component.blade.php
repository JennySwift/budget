<script id="home-page-template" type="x-template">

    <div>
        @include('main.home.toolbar')

        <new-transaction
            :show="show"
            :tab="tab"
        >
        </new-transaction>

        <div class="main-content">

            <totals
            :show="show"
            >
            </totals>

            <div class="flex-grow-2">
                {{--@include('templates.home.popups.index')--}}
                <transactions
                        :show="show"
                >
                </transactions>
            </div>

            <div v-show="tab === 'graphs'" class="flex-grow-2">
                <graphs></graphs>
            </div>

            <filter
                :show="show"
            ></filter>

        </div>
    </div>

</script>