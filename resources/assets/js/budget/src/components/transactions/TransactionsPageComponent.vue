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

    export default {
        data: function () {
            return {
                // page: 'home',
                shared: store.state,
                colors: {},
                hoveringTotalsButton: false
            };
        },
        components: {},
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