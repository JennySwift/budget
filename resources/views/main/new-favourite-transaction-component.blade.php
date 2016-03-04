<script id="new-favourite-transaction-template" type="x-template">

    <div id="new-favourite">
        <h2>Create a new favourite transaction</h2>

        <div class="form-group">
            <label for="new-favourite-name">Name</label>
            <input
                v-model="newFavourite.name"
                type="text"
                id="new-favourite-name"
                name="new-favourite-name"
                placeholder="name"
                class="form-control"
            >
        </div>

        <div>
            <label>Type</label>
            <select v-model="newFavourite.type" class="form-control">
                <option value="income">Credit</option>
                <option value="expense">Debit</option>
                {{--<option value="transfer">Transfer</option>--}}
            </select>
        </div>

        <div class="form-group">
            <label for="new-favourite-description">Description</label>
            <input
                v-model="newFavourite.description"
                type="text"
                id="new-favourite-description"
                name="new-favourite-description"
                placeholder="description"
                class="form-control"
            >
        </div>

        <div class="form-group">
            <label for="new-favourite-merchant">Merchant</label>
            <input
                v-model="newFavourite.merchant"
                type="text"
                id="new-favourite-merchant"
                name="new-favourite-merchant"
                placeholder="merchant"
                class="form-control"
            >
        </div>

       <div class="form-group">
           <label for="new-favourite-total">Total</label>
           <input
               v-model="newFavourite.total"
               type="text"
               id="new-favourite-total"
               name="new-favourite-total"
               placeholder="total"
               class="form-control"
           >
       </div>

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

        <budget-autocomplete
                :chosen-budgets.sync="newFavourite.budgets"
                :budgets="budgets"
                multiple-budgets="true"
                :function-on-enter="insertFavouriteTransaction"
        >
        </budget-autocomplete>

        <div>
            <button
                    v-on:click="insertFavouriteTransaction()"
                    class="btn btn-success">
                Add new favourite
            </button>
        </div>

    </div>

</script>