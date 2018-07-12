<template>

    <div class="form-group" id="new-transaction-favourites">
        <multiselect
            v-model="shared.newTransaction"
            :options="shared.favouriteTransactions"
            track-by="id"
            label="name"
            @select="fillFields"
            @open="onOpen"
            :close-on-select="true"
            :hide-selected="true"
            open-direction="bottom"
            placeholder="Select a favourite"
        >
        </multiselect>
    </div>

</template>

<script>
    import NewTransactionRepository from "../../repositories/NewTransactionRepository";

    export default {
        data: function () {
            return {
                shared: store.state,
            };
        },
        methods: {

            onOpen: NewTransactionRepository.onOpen,
            fillFields: function (transaction) {
                NewTransactionRepository.fillFields(transaction);
            },
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style lang="scss" type="text/scss">

</style>