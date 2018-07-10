<template>

    <popup
        popup-name="favourite-transaction"
        id="favourite-transaction-popup"
        :save="updateFavouriteTransaction"
        :destroy="deleteFavouriteTransaction"
        :redirect-to="redirectTo"
    >
        <div slot="content">
            <div class="form-group">
                <label for="selected-favourite-name">Name</label>
                <input
                    v-model="shared.selectedFavouriteTransaction.name"
                    type="text"
                    id="selected-favourite-name"
                    name="selected-favourite-name"
                    placeholder="name"
                    class="form-control"
                >
            </div>

            <div class="form-group">
                <label for="selected-favourite-type">Type</label>

                <select
                    v-model="shared.selectedFavouriteTransaction.type"
                    id="selected-favourite-type"
                    class="form-control"
                >
                    <option
                        v-for="type in types"
                        v-bind:value="type"
                    >
                        {{ type }}
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label for="selected-favourite-description">Description</label>
                <input
                    v-model="shared.selectedFavouriteTransaction.description"
                    type="text"
                    id="selected-favourite-description"
                    name="selected-favourite-description"
                    placeholder="description"
                    class="form-control"
                >
            </div>

            <div class="form-group">
                <label for="selected-favourite-merchant">Merchant</label>
                <input
                    v-model="shared.selectedFavouriteTransaction.merchant"
                    type="text"
                    id="selected-favourite-merchant"
                    name="selected-favourite-merchant"
                    placeholder="merchant"
                    class="form-control"
                >
            </div>

            <div class="form-group">
                <label for="selected-favourite-total">Total</label>
                <input
                    v-model="shared.selectedFavouriteTransaction.total"
                    type="text"
                    id="selected-favourite-total"
                    name="selected-favourite-total"
                    placeholder="total"
                    class="form-control"
                >
            </div>

            <!--Account-->
            <div v-if="shared.selectedFavouriteTransaction.type !== 'transfer'" class="form-group">
                <label for="selected-favourite-account">Account</label>

                <select
                    v-model="shared.selectedFavouriteTransaction.account"
                    id="selected-favourite-account"
                    class="form-control"
                >
                    <option
                        v-for="account in shared.accounts"
                        v-bind:value="account"
                    >
                        {{ account.name }}
                    </option>
                </select>
            </div>

            <!--From account-->
            <div v-if="shared.selectedFavouriteTransaction.type === 'transfer'" class="form-group">
                <label for="selected-favourite-from-account">From Account</label>

                <select
                    v-model="shared.selectedFavouriteTransaction.fromAccount"
                    id="selected-favourite-from-account"
                    class="form-control"
                >
                    <option
                        v-for="account in shared.accounts"
                        v-bind:value="account"
                    >
                        {{ account.name }}
                    </option>
                </select>
            </div>

            <!--To account-->
            <div v-if="shared.selectedFavouriteTransaction.type === 'transfer'" class="form-group">
                <label for="selected-favourite-to-account">To Account</label>

                <select
                    v-model="shared.selectedFavouriteTransaction.toAccount"
                    id="selected-favourite-to-account"
                    class="form-control"
                >
                    <option
                        v-for="account in shared.accounts"
                        v-bind:value="account"
                    >
                        {{ account.name }}
                    </option>
                </select>
            </div>

            <autocomplete
                autocomplete-id="favourite-transaction-budgets-autocomplete"
                input-id="favourite-transaction-input"
                :unfiltered-options="shared.budgets"
                prop="name"
                multiple-selections="true"
                :chosen-options="shared.selectedFavouriteTransaction.budgets"
                :function-on-enter="updateFavouriteTransaction"
            >
            </autocomplete>

        </div>

    </popup>

</template>

<script>
    import helpers from '../../repositories/helpers/Helpers'
    export default {
        data: function () {
            return {
                shared: store.state,
                types: ["income", "expense", "transfer"],
                redirectTo: '/favourite-transactions'
            };
        },
        components: {},
        methods: {

            /**
             *
             */
            updateFavouriteTransaction: function () {
                var data = store.setFavouriteTransactionFields(this.shared.selectedFavouriteTransaction);

                helpers.put({
                    url: '/api/favouriteTransactions/' + this.shared.selectedFavouriteTransaction.id,
                    data: data,
                    property: 'favouriteTransactions',
                    message: 'FavouriteTransaction updated',
                    redirectTo: this.redirectTo,
                    callback: function (response) {
                        store.updateFavouriteTransaction(response);
                    }.bind(this)
                });
            },

            /**
             *
             */
            deleteFavouriteTransaction: function () {
                helpers.delete({
                    url: '/api/favouriteTransactions/' + this.shared.selectedFavouriteTransaction.id,
                    array: 'favouriteTransactions',
                    itemToDelete: this.favouriteTransaction,
                    message: 'FavouriteTransaction deleted',
                    redirectTo: this.redirectTo,
                    callback: function () {
                        store.deleteFavouriteTransaction(this.shared.selectedFavouriteTransaction);
                    }.bind(this)
                });
            }
        },
        props: [],
        mounted: function () {

        }
    }
</script>