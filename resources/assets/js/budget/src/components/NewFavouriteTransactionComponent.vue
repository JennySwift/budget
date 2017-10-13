<template>
    <div id="new-favourite">
        <h3>Create a new favourite transaction</h3>

        <div class="form-group">
            <label for="new-favourite-name">Name</label>
            <input
                v-model="newFavourite.name"
                <!--v-on:keyup.13="insertFavouriteTransaction()"-->
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
                    {{ type.name }}
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

        <!--Account-->
        <div v-show="showFields && newFavourite.type !== 'transfer'" class="form-group">
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
                    {{ account.name }}
                </option>
            </select>
        </div>

        <!--From account-->
        <div v-show="showFields && newFavourite.type === 'transfer'" class="form-group">
            <label for="new-favourite-transaction-from-account">From Account</label>

            <select
                v-model="newFavourite.fromAccount"
                v-on:keyup.13="insertFavouriteTransaction()"
                id="new-favourite-transaction-account"
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
        <div v-show="showFields && newFavourite.type === 'transfer'" class="form-group">
            <label for="new-favourite-transaction-to-account">To Account</label>

            <select
                v-model="newFavourite.toAccount"
                v-on:keyup.13="insertFavouriteTransaction()"
                id="new-favourite-transaction-account"
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

</template>

<script>
    export default {
        data: function () {
            return {
                newFavourite: {
                    account: {},
                    fromAccount: {},
                    toAccount: {},
                    budgets: [],
                    type: 'expense'
                },
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
                $.event.trigger('show-loading');
                var data = FavouriteTransactionsRepository.setFields(this.newFavourite);

                this.$http.post('/api/favouriteTransactions', data, function (response) {
                    this.favouriteTransactions.push(response);
                    this.showFields = false;
                    this.emptyFields();
                    $.event.trigger('provide-feedback', ['Favourite created', 'success']);
                    $.event.trigger('hide-loading');
                })
                    .error(function (response) {
                        HelpersRepository.handleResponseError(response);
                    });
            },

            /**
             *
             */
            emptyFields: function () {
                this.newFavourite.name = '';
                this.newFavourite.description = '';
                this.newFavourite.merchant = '';
                this.newFavourite.total = '';
                this.newFavourite.budgets = [];
            },

            /**
             * Set the default new favourite account to the first account, when the accounts are loaded
             */
            setNewFavouriteAccount: function () {
                var that = this;
                setTimeout(function () {
                    that.newFavourite.account = that.accounts[0];
                }, 500);
            }
        },
        props: [
            'budgets',
            'favouriteTransactions',
            'accounts'
        ],
        mounted: function () {
            this.setNewFavouriteAccount();
        }
    }
</script>
