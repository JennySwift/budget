<script id="new-transaction-template" type="x-template">

    <div
            v-show="tab === 'transactions'"
            id="new-transaction-container"
    >
        <div
                v-show="showNewTransaction"
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
                @include('main.home.new-transaction.type')
            </div>

            <div>
                @include('main.home.new-transaction.date')
                @include('main.home.new-transaction.total')
            </div>

            <div>
                @include('main.home.new-transaction.merchant')
                @include('main.home.new-transaction.description')
            </div>

            <div>
                @include('main.home.new-transaction.accounts')
                @include('main.home.new-transaction.reconcile')
            </div>

            <div>
                @include('main.home.new-transaction.tags')
                @include('main.home.new-transaction.duration')
            </div>

            <div>
                @include('main.home.new-transaction.enter')
            </div>

        </div>


    </div>

</script>