<template>
    <div>

        <h3 v-if="transactions.length === 0">No transactions to show.</h3>

        <edit-transaction-popup
            :transactions.sync="transactions"
            :accounts="accountsRepository.accounts"
        >
        </edit-transaction-popup>

        <allocation-popup></allocation-popup>

        <table v-if="transactions.length > 0" id="transactions" class="">

            <!--Table header-->
            <thead>

            <tr>

                <th v-show="transactionPropertiesToShow.date">Date</th>

                <th v-show="transactionPropertiesToShow.description">Description</th>

                <th v-show="transactionPropertiesToShow.merchant">Merchant</th>

                <th v-show="transactionPropertiesToShow.total">
                    <span class="total fa fa-dollar"></span>
                </th>

                <th v-show="transactionPropertiesToShow.account">Account</th>

                <th v-show="transactionPropertiesToShow.duration">Duration</th>

                <th v-show="transactionPropertiesToShow.reconciled" class="reconcile">R</th>

                <th v-show="transactionPropertiesToShow.allocated">Allocation</th>

            </tr>

            </thead>

            <tbody
                v-for="transaction in transactions"
                is="transaction"
                :transaction-properties-to-show="transactionPropertiesToShow"
                :transactions.sync="transactions"
                :transaction="transaction"
                v-bind:style="{color: me.preferences.colors[transaction.type]}"
                :id="transaction.id"
                class="add_to_search_total results-transaction-tbody {{ transaction.type }}">
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                me: me,
                accountsRepository: AccountsRepository.state,
                transactionsRepository: TransactionsRepository.state,
                showStatus: false,
                showDate: true,
                showDescription: true,
                showMerchant: true,
                showTotal: true,
                showType: true,
                showAccount: true,
                showDuration: true,
                showReconciled: true,
                showAllocated: true,
                showBudgets: true,
                showDelete: true,
            };
        },
        computed: {
            transactions: function () {
                return this.transactionsRepository.transactions;
            }
        },
        components: {},
        methods: {

        },
        props: [
            'transactionPropertiesToShow'
        ],
        mounted: function () {

        }
    }
</script>