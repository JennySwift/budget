<template>
    <div id="new-favourite">
        <h3>Create a new favourite transaction</h3>

        <div class="form-group">
            <label for="new-favourite-name">Name</label>
            <input
                v-model="shared.newFavouriteTransaction.name"
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
                v-model="shared.newFavouriteTransaction.type"
                v-on:keyup.13="insertFavouriteTransaction()"
                id="new-favourite-type"
                class="form-control"
            >
                <option
                    v-for="type in types"
                    v-bind:value="type.value"
                >
                    {{ type.name }}
                </option>
            </select>
        </div>

        <div v-show="showFields" class="form-group">
            <label for="new-favourite-description">Description</label>
            <input
                v-model="shared.newFavouriteTransaction.description"
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
                v-model="shared.newFavouriteTransaction.merchant"
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
                v-model="shared.newFavouriteTransaction.total"
                v-on:keyup.13="insertFavouriteTransaction()"
                type="text"
                id="new-favourite-total"
                name="new-favourite-total"
                placeholder="total"
                class="form-control"
            >
        </div>

        <!--Account-->
        <div v-show="showFields && shared.newFavouriteTransaction.type !== 'transfer'" class="form-group">
            <label for="new-favourite-transaction-account">Account</label>

            <select
                v-model="shared.newFavouriteTransaction.account"
                v-on:keyup.13="insertFavouriteTransaction()"
                id="new-favourite-transaction-account"
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
        <div v-show="showFields && shared.newFavouriteTransaction.type === 'transfer'" class="form-group">
            <label for="new-favourite-transaction-from-account">From Account</label>

            <select
                v-model="shared.newFavouriteTransaction.fromAccount"
                v-on:keyup.13="insertFavouriteTransaction()"
                id="new-favourite-transaction-account"
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
        <div v-show="showFields && shared.newFavouriteTransaction.type === 'transfer'" class="form-group">
            <label for="new-favourite-transaction-to-account">To Account</label>

            <select
                v-model="shared.newFavouriteTransaction.toAccount"
                v-on:keyup.13="insertFavouriteTransaction()"
                id="new-favourite-transaction-account"
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

        <div v-show="showFields" class="form-group">
            <label>Budgets</label>

            <autocomplete
                autocomplete-id="new-favourite-transaction-budgets-autocomplete"
                input-id="new-favourite-transaction-input"
                :unfiltered-options="shared.budgets"
                prop="name"
                multiple-selections="true"
                :chosen-options="shared.newFavouriteTransaction.budgets"
                :function-on-enter="insertFavouriteTransaction"
            >
            </autocomplete>
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

</template>

<script>
    import helpers from '../../repositories/helpers/Helpers'
    export default {
        data: function () {
            return {
                shared: store.state,
                showFields: false,
                types: [
                    {
                        name: 'credit', value: 'income'
                    },
                    {
                        name: 'debit', value: 'expense'
                    },
                    {
                        name: 'transfer', value: 'transfer'
                    }
                ]
            };
        },
        components: {},
        methods: {

            /**
             *
             */
            insertFavouriteTransaction: function () {
                var data = store.setFavouriteTransactionFields();

                helpers.post({
                    url: '/api/favouriteTransactions',
                    data: data,
                    array: 'favouriteTransactions',
                    message: 'Favourite transaction created',
                    clearFields: this.clearFields,
                    redirectTo: this.redirectTo,
                    callback: function () {
                        this.showFields = false;
                    }.bind(this)
                });
            },

            /**
             *
             */
            clearFields: function () {
                store.set('', 'newFavouriteTransaction.name');
                store.set('', 'newFavouriteTransaction.description');
                store.set('', 'newFavouriteTransaction.merchant');
                store.set('', 'newFavouriteTransaction.total');
                store.set([], 'newFavouriteTransaction.budgets');
            },
        },
        props: [],
        mounted: function () {

        }
    }
</script>
