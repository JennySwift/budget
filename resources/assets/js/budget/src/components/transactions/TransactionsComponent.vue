<template>
    <div>

        <h3 v-if="shared.transactions.length === 0">No transactions to show.</h3>

        <edit-transaction-popup></edit-transaction-popup>

        <allocation-popup></allocation-popup>

        <table v-if="shared.transactions.length > 0" id="transactions" class="">

            <!--Table header-->
            <thead>

            <tr>

                <th v-show="shared.transactionPropertiesToShow.date">Date</th>

                <th v-show="shared.transactionPropertiesToShow.description">Description</th>

                <th v-show="shared.transactionPropertiesToShow.merchant">Merchant</th>

                <th v-show="shared.transactionPropertiesToShow.total">
                    <span class="total fa fa-dollar"></span>
                </th>

                <th v-show="shared.transactionPropertiesToShow.account">Account</th>

                <th v-show="shared.transactionPropertiesToShow.duration">Duration</th>

                <th v-show="shared.transactionPropertiesToShow.reconciled" class="reconcile">R</th>

                <th v-show="shared.transactionPropertiesToShow.allocated">Allocation</th>

            </tr>

            </thead>

            <tbody
                v-for="transaction in shared.transactions"
                is="transaction"
                :transaction="transaction"
                v-bind:style="{color: shared.me.preferences.colors[transaction.type]}"
                :id="transaction.id"
                :class="'add_to_search_total results-transaction-tbody' + transaction.type">
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                shared: store.state,
            };
        },
        components: {},
        methods: {

        },
        props: [],
        mounted: function () {

        }
    }
</script>