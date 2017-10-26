<template>
    <div id="transactions-page">
        <new-transaction></new-transaction>

        <div id="totals-transactions-filter-container">
            <totals></totals>

            <transactions></transactions>

            <transactions-filter></transactions-filter>
        </div>
    </div>

</template>

<script>
    import TotalsRepository from '../../repositories/TotalsRepository'
    import TransactionsComponent from './TransactionsComponent.vue'
    import NewTransactionComponent from '../../components/new_transaction/NewTransactionComponent.vue'
    import TotalsComponent from '../../components/TotalsComponent.vue'
    import FilterComponent from '../../components/filter/FilterComponent.vue'

    export default {
        data: function () {
            return {
                // page: 'home',
                shared: store.state,
                colors: {},
                hoveringTotalsButton: false
            };
        },
        components: {
            'transactions': TransactionsComponent,
            'new-transaction': NewTransactionComponent,
            'totals': TotalsComponent,
            'transactions-filter': FilterComponent
        },
        computed: {
            // tab: function () {
            //     return this.homePageRepository.tab;
            // }
        },
        methods: {

            /**
             *
             * @param tab
             */
            // switchTab: function (tab) {
            //     HomePageRepository.setTab(tab);
            //     FilterRepository.runFilter(this);
            // },

            /**
             *
             */
            respondToMouseEnterOnTotalsButton: function () {
                TotalsRepository.respondToMouseEnterOnTotalsButton(this);
            },

            /**
             *
             */
            respondToMouseLeaveOnTotalsButton: function () {
                TotalsRepository.respondToMouseLeaveOnTotalsButton(this);
            },

            /**
             *
             */
            toggleNewTransaction: function () {
                store.toggle('show.newTransaction');
            }
        },
        props: [],
        mounted: function () {

        }
    }
</script>