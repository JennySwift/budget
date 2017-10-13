<template>
    <div>
        <!--Toolbar-->
        <div id="toolbar">

            <div>

                <button
                    v-if="!show.newTransaction"
                    v-on:click="toggleNewTransaction()"
                    class="btn btn-info">
                    New transaction
                </button>

                <button
                    v-if="show.newTransaction"
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

        <new-transaction
            :show="show"
            :tab="tab"
            :budgets="budgets"
        >
        </new-transaction>

        <totals
            :show="show"
        >
        </totals>

        <transactions
            :show="show"
            :transaction-properties-to-show="transactionPropertiesToShow",
        >
        </transactions>

        <mass-transaction-update-popup
            :budgets="budgets"
        >
        </mass-transaction-update-popup>

        <filter
            :show="show"
            :budgets="budgets"
        ></filter>
    </div>

</template>

<script>
    export default {
        data: function () {
            return {
                // page: 'home',
                budgetsRepository: BudgetsRepository.state,
                colors: {},
                env: env,
                homePageRepository: HomePageRepository.state,
                hoveringTotalsButton: false
            };
        },
        components: {},
        computed: {
            budgets: function () {
                return this.budgetsRepository.budgets;
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
                $.event.trigger('toggle-new-transaction');
            }
        },
        props: [
            'show',
            'transactionPropertiesToShow'
        ],
        mounted: function () {

        }
    }
</script>