<template>
    <div class="form-group">
        <autocomplete
            input-label="Favourites"
            id-to-focus-after-autocomplete=""
            input-id="new-transaction-favourites"
            :unfiltered-options="shared.favouriteTransactions"
            prop="name"
            label-for-option=""
            :function-when-option-is-chosen="fillFields"
            :selected.sync="selectedFavouriteTransaction"
            clear-field-on-focus="true"
        >
        </autocomplete>

    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                shared: store.state,
                selectedFavouriteTransaction: {},
            };
        },
        methods: {
            /**
             *
             */
            fillFields: function () {
                store.set(this.selectedFavouriteTransaction.description, 'newTransaction.description');
                store.set(this.selectedFavouriteTransaction.merchant, 'newTransaction.merchant');
                store.set(this.selectedFavouriteTransaction.total, 'newTransaction.total');
                store.set(this.selectedFavouriteTransaction.type, 'newTransaction.type');


                if (this.shared.newTransaction.type === 'transfer') {
                    store.set(this.selectedFavouriteTransaction.fromAccount, 'newTransaction.fromAccount');
                    store.set(this.selectedFavouriteTransaction.toAccount, 'newTransaction.toAccount');
                }
                else {
                    store.set(this.selectedFavouriteTransaction.account, 'newTransaction.account');
                }
            },
        }
    }
</script>