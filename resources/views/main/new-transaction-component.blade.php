<script id="new-transaction-template" type="x-template">

    <div
            v-show="tab === 'transactions'"
            id="new-transaction-container"
    >


        {{--This div is so the is has same margin as other--}}
        <div
                v-show="show.new_transaction"
                v-bind:style="{color: colors[new_transaction.type]}"
                id="new-transaction"
        >

            <label>Favourites</label>

            <select
                    v-options="item.name for item in favouriteTransactions"
                    v-model="selectedFavouriteTransaction"
                    v-on:change="fillFields()"
                    class="form-control">
            </select>

            <div class="type">
                @include('templates.home.new-transaction.type')
            </div>

            <div>
                @include('templates.home.new-transaction.date')
                @include('templates.home.new-transaction.total')
            </div>

            <div>
                @include('templates.home.new-transaction.merchant')
                @include('templates.home.new-transaction.description')
            </div>

            <div>
                @include('templates.home.new-transaction.accounts')
                @include('templates.home.new-transaction.reconcile')
            </div>

            <div>
                @include('templates.home.new-transaction.tags')
                @include('templates.home.new-transaction.duration')
            </div>

            <div>
                @include('templates.home.new-transaction.enter')
            </div>

        </div>


    </div>

</script>