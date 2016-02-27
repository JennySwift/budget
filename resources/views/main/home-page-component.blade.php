<script id="home-page-template" type="x-template">

    <div>
        @include('main.home.toolbar')

        <new-transaction></new-transaction>

        <div class="main-content">

            {{--<side-bar-totals--}}
            {{--v-show="show.basicTotals || show.budgetTotals"--}}
            {{--show="show"--}}
            {{-->--}}
            {{--</side-bar-totals>--}}

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