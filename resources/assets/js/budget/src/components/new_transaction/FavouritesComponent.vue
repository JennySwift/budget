<template>
    <div class="form-group">
        <autocomplete
            input-label="Favourites"
            id-to-focus-after-autocomplete=""
            autocomplete-id="new-transaction-favourites"
            input-id="new-transaction-favourites"
            :unfiltered-options="shared.favouriteTransactions"
            prop="name"
            label-for-option=""
            :function-when-option-is-chosen="fillFields"
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
            };
        },
        methods: {

            optionChosen: function (option, inputId) {
                if (inputId === 'new-transaction-favourites') {
                    this.fillFields(option);
                }
            },

            /**
             *
             */
            fillFields: function (selectedFavouriteTransaction) {
                store.set(selectedFavouriteTransaction.description, 'newTransaction.description');
                store.set(selectedFavouriteTransaction.merchant, 'newTransaction.merchant');
                store.set(selectedFavouriteTransaction.total, 'newTransaction.total');
                store.set(selectedFavouriteTransaction.type, 'newTransaction.type');


                if (this.shared.newTransaction.type === 'transfer') {
                    store.set(selectedFavouriteTransaction.fromAccount, 'newTransaction.fromAccount');
                    store.set(selectedFavouriteTransaction.toAccount, 'newTransaction.toAccount');
                }
                else {
                    store.set(selectedFavouriteTransaction.account, 'newTransaction.account');
                }
            },
        },
        created: function () {
            this.$bus.$on('autocomplete-option-chosen', this.optionChosen);
        }
    }
</script>