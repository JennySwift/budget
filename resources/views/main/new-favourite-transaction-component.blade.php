<script id="new-favourite-transaction-template" type="x-template">

    <div id="new-favourite">
        <h2>Create a new favourite transaction</h2>

        <div>
            <label>Name your new favourite transaction</label>
            <input v-model="newFavourite.name" type="text" placeholder="name"/>
        </div>

        <div>
            <label>Type</label>
            <select v-model="newFavourite.type" class="form-control">
                <option value="income">Credit</option>
                <option value="expense">Debit</option>
                {{--<option value="transfer">Transfer</option>--}}
            </select>
        </div>

        <div>
            <label>Description</label>
            <input v-model="newFavourite.description" type="text" placeholder="description"/>
        </div>

        <div>
            <label>Merchant</label>
            <input v-model="newFavourite.merchant" type="text" placeholder="merchant"/>
        </div>

        <div>
            <label>Total</label>
            <input v-model="newFavourite.total" type="text" placeholder="total"/>
        </div>

        <div>
            <div class="form-group">
                <label for="new-favourite-transaction-account">Account</label>

                <select
                        v-model="newFavourite.account"
                        id="new-favourite-transaction-account"
                        class="form-control"
                >
                    <option
                            v-for="account in accounts"
                            v-bind:value="account"
                    >
                        @{{ account.name }}
                    </option>
                </select>
            </div>

        </div>

        {{--<budget-autocomplete--}}
        {{--chosenTags="newFavourite.budgets"--}}
        {{--dropdown="newFavourite.dropdown"--}}
        {{--tags="budgets"--}}
        {{--multipleTags="true">--}}
        {{--</budget-autocomplete>--}}

        <div>
            <button
                    v-on:click="insertFavouriteTransaction()"
                    class="btn btn-success">
                Add new favourite
            </button>
        </div>

    </div>

</script>