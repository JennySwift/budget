<template>
    <div v-if="shared.newTransaction.type !== 'transfer'">
        <label for="">Budgets</label>

        <multiselect
            v-model="shared.newTransaction.budgets"
            :options="shared.budgets"
            placeholder="Add a budget..."
            label="name"
            track-by="id"
            :multiple="true"
        >
        </multiselect>

    </div>
</template>

<script>
    import NewTransactionRepository from '../../repositories/NewTransactionRepository'
    import Multiselect from 'vue-multiselect'
    export default {
        data: function () {
            return {
                shared: store.state
            };
        },
        components: {
            Multiselect
        },
        methods: {
            insertTransaction () {
                NewTransactionRepository.insertTransactionSetup();
            },
        },
    }
</script>