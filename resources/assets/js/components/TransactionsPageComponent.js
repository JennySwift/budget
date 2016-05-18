var TransactionsPage = Vue.component('transactions-page', {
    template: '#transactions-page-template',
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
    ready: function () {

    }
});