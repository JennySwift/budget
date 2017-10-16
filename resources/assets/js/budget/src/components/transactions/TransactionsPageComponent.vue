<template>
    <div>
        <!--Toolbar-->
        <div id="toolbar">

            <div>

                <button
                    v-if="!shared.show.newTransaction"
                    v-on:click="toggleNewTransaction()"
                    class="btn btn-info">
                    New transaction
                </button>

                <button
                    v-if="shared.show.newTransaction"
                    v-on:click="toggleNewTransaction()"
                    class="btn btn-info">
                    Hide new transaction
                </button>

            </div>

            <div>
                <button
                    v-on:mouseenter="respondToMouseEnterOnTotalsButton"
                    v-on:mouseleave="respondToMouseLeaveOnTotalsButton"
                    class="btn btn-default totals-btn"
                >
                    Totals
                </button>
            </div>
        </div>

        <new-transaction></new-transaction>

        <totals></totals>

        <transactions></transactions>

        <mass-transaction-update-popup></mass-transaction-update-popup>

        <filter></filter>
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
            budgets: function () {
                return this.shared.budgets;
            },
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