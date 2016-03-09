<script id="new-favourite-transaction-template" type="x-template">

    <div id="new-favourite">
        <h3>Create a new favourite transaction</h3>

        <div class="form-group">
            <label for="new-favourite-name">Name</label>
            <input
                v-model="newFavourite.name"
                {{--v-on:keyup.13="insertFavouriteTransaction()"--}}
                v-on:focus="showFields = true"
                type="text"
                id="new-favourite-name"
                name="new-favourite-name"
                placeholder="name"
                class="form-control"
            >
        </div>

        <div v-show="showFields" class="form-group">
            <label for="new-favourite-type">Type</label>

            <select
                v-model="newFavourite.type"
                v-on:keyup.13="insertFavouriteTransaction()"
                id="new-favourite-type"
                class="form-control"
            >
                <option
                    v-for="type in types"
                    v-bind:value="type.value"
                >
                    @{{ type.name }}
                </option>
            </select>
        </div>

        <div v-show="showFields" class="form-group">
            <label for="new-favourite-description">Description</label>
            <input
                v-model="newFavourite.description"
                v-on:keyup.13="insertFavouriteTransaction()"
                type="text"
                id="new-favourite-description"
                name="new-favourite-description"
                placeholder="description"
                class="form-control"
            >
        </div>

        <div v-show="showFields" class="form-group">
            <label for="new-favourite-merchant">Merchant</label>
            <input
                v-model="newFavourite.merchant"
                v-on:keyup.13="insertFavouriteTransaction()"
                type="text"
                id="new-favourite-merchant"
                name="new-favourite-merchant"
                placeholder="merchant"
                class="form-control"
            >
        </div>

       <div v-show="showFields" class="form-group">
           <label for="new-favourite-total">Total</label>
           <input
               v-model="newFavourite.total"
               v-on:keyup.13="insertFavouriteTransaction()"
               type="text"
               id="new-favourite-total"
               name="new-favourite-total"
               placeholder="total"
               class="form-control"
           >
       </div>

        <div v-show="showFields" class="form-group">
            <label for="new-favourite-transaction-account">Account</label>

            <select
                    v-model="newFavourite.account"
                    v-on:keyup.13="insertFavouriteTransaction()"
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

        <div v-show="showFields" class="form-group">
            <label>Budgets</label>

            <budget-autocomplete
                    :chosen-budgets.sync="newFavourite.budgets"
                    :budgets="budgets"
                    multiple-budgets="true"
                    :function-on-enter="insertFavouriteTransaction"
            >
            </budget-autocomplete>
        </div>

        <div v-show="showFields" class="form-group">
            <div class="buttons">
                <button
                        v-on:click="showFields = false"
                        class="btn btn-default">
                    Cancel
                </button>
                <button
                        v-on:click="insertFavouriteTransaction()"
                        class="btn btn-success">
                    Add new favourite
                </button>
            </div>
        </div>

    </div>

</script>