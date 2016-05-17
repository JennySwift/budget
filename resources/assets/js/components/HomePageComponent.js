var HomePage = Vue.component('home-page', {
    template: '#home-page-template',
    data: function () {
        return {
            page: 'home',
            budgetsRepository: BudgetsRepository.state,
            transactions: [],
            colors: {},
            env: env,
            tab: this.setTab(),
            hoveringTotalsButton: false
        };
    },
    components: {},
    computed: {
        budgets: function () {
          return this.budgetsRepository.budgets;
        },
    },
    methods: {

        /**
         *
         * @returns {string}
         */
        setTab: function () {
            if (this.env === 'local') {
                return 'transactions';
            }
            else {
                return 'transactions';
            }
        },

        /**
         *
         * @param tab
         */
        switchTab: function (tab) {
            this.tab = tab;
            $.event.trigger('run-filter');
        },

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