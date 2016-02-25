<script id="new-transaction-template" type="x-template">

    <div
            v-show="tab === 'transactions'"
            id="new-transaction-container"
    >

        <div
                v-show="show.newTransaction"
                v-bind:style="{color: colors[newTransaction.type]}"
                id="new-transaction"
        >

            <div class="form-group">
                <label for="new-transaction-favourites">Favourites</label>

                <select
                    v-model="selectedFavouriteTransaction"
                    v-on:change="fillFields()"
                    id="new-transaction-favourites"
                    class="form-control"
                >
                    <option
                        v-for="favourite in favouriteTransactions"
                        v-bind:value="favourite"
                    >
                        @{{ favourite.name }}
                    </option>
                </select>
            </div>

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