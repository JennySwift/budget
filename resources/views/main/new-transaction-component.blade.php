<script id="new-transaction-template" type="x-template">

    <div
            v-show="tab === 'transactions'"
            id="new-transaction-container"
    >
        <div
                v-show="showNewTransaction"
                v-bind:style="{color: me.preferences.colors[newTransaction.type]}"
                id="new-transaction"
        >
            
            <pre>@{{$data.newTransaction | json}}</pre>

            <div class="form-group">
                <autocomplete
                    input-label="Favourites"
                    id-to-focus-after-autocomplete=""
                    autocomplete-id="new-transaction-favourites"
                    autocomplete-field-id="new-transaction-favourites-input"
                    :unfiltered-autocomplete-options="favouriteTransactions"
                    prop="name"
                    label-for-option=""
                    :function-on-enter=""
                    :function-when-option-is-chosen="fillFields"
                    :model.sync="selectedFavouriteTransaction"
                    clear-field-on-focus="true"
                >
                </autocomplete>
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
                @include('main.home.new-transaction.budgets')
                @include('main.home.new-transaction.duration')
            </div>

            <div>
                @include('main.home.new-transaction.enter')
            </div>

        </div>


    </div>

</script>