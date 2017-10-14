<template>
    <div
        v-show="showPopup"
        v-on:click="closePopup($event)"
        class="popup-outer"
    >

        <div id="update-favourite-transaction-popup" class="popup-inner">

            <div class="form-group">
                <label for="selected-favourite-name">Name</label>
                <input
                    v-model="selectedFavourite.name"
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
                    v-model="selectedFavourite.type"
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
                    v-model="selectedFavourite.description"
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
                    v-model="selectedFavourite.merchant"
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
                    v-model="selectedFavourite.total"
                    type="text"
                    id="selected-favourite-total"
                    name="selected-favourite-total"
                    placeholder="total"
                    class="form-control"
                >
            </div>

            <!--Account-->
            <div v-if="selectedFavourite.type !== 'transfer'" class="form-group">
                <label for="selected-favourite-account">Account</label>

                <select
                    v-model="selectedFavourite.account"
                    id="selected-favourite-account"
                    class="form-control"
                >
                    <option
                        v-for="account in accounts"
                        v-bind:value="account"
                    >
                        {{ account.name }}
                    </option>
                </select>
            </div>

            <!--From account-->
            <div v-if="selectedFavourite.type === 'transfer'" class="form-group">
                <label for="selected-favourite-from-account">From Account</label>

                <select
                    v-model="selectedFavourite.fromAccount"
                    id="selected-favourite-from-account"
                    class="form-control"
                >
                    <option
                        v-for="account in accounts"
                        v-bind:value="account"
                    >
                        {{ account.name }}
                    </option>
                </select>
            </div>

            <!--To account-->
            <div v-if="selectedFavourite.type === 'transfer'" class="form-group">
                <label for="selected-favourite-to-account">To Account</label>

                <select
                    v-model="selectedFavourite.toAccount"
                    id="selected-favourite-to-account"
                    class="form-control"
                >
                    <option
                        v-for="account in accounts"
                        v-bind:value="account"
                    >
                        {{ account.name }}
                    </option>
                </select>
            </div>

            <budget-autocomplete
                :chosen-budgets.sync="selectedFavourite.budgets"
                :budgets="budgets"
                multiple-budgets="true"
            >
            </budget-autocomplete>

            <div class="buttons">
                <button v-on:click="showPopup = false" class="btn btn-default">Cancel</button>
                <button v-on:click="deleteFavouriteTransaction()" class="btn btn-danger">Delete</button>
                <button v-on:click="updateFavouriteTransaction()" class="btn btn-success">Save</button>
            </div>

        </div>
    </div>
</template>

<script>
    import helpers from '../../repositories/Helpers'
    export default {
        data: function () {
            return {
                showPopup: false,
                selectedFavourite: {},
                types: ["income", "expense", "transfer"],
            };
        },
        components: {},
        methods: {

            /**
             *
             */
            closePopup: function ($event) {
                helpers.closePopup($event, this);
            },

            /**
             *
             */
            updateFavouriteTransaction: function () {
                var data = store.setFavouriteTransactionFields(this.selectedFavourite);

                helpers.put({
                    url: '/api/favouriteTransactions/' + this.selectedFavourite.id,
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
                    url: '/api/favouriteTransactions/' + this.selectedFavourite.id,
                    array: 'favouriteTransactions',
                    itemToDelete: this.favouriteTransaction,
                    message: 'FavouriteTransaction deleted',
                    redirectTo: this.redirectTo,
                    callback: function () {
                        store.deleteFavouriteTransaction(this.selectedFavourite);
                    }.bind(this)
                });
            },

            /**
             *
             */
            listen: function () {
                var that = this;
                $(document).on('show-update-favourite-transaction-popup', function (event, favourite) {
                    that.selectedFavourite = favourite;
                    that.showPopup = true;
                });
            }
        },
        props: [],
        mounted: function () {
            this.listen();
        }
    }
</script>