var HomePage = Vue.component('home-page', {
    template: '#home-page-template',
    data: function () {
        return {
            page: 'home',
            budgets: [],
            transactions: [],
            colors: {},
            tab: '',
            env: env
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        getTransactions: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/transactions', function (response) {
                    this.transactions = response;
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    HelpersRepository.handleResponseError(response);
                });
        },

        /**
        *
        */
        getBudgets: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/budgets', function (response) {
                this.budgets = response;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         * @param tab
         */
        switchTab: function (tab) {
            this.tab = tab;

            //if (tab === 'transactions') {
            //    this.show.basicTotals = true;
            //    this.show.budgetTotals = true;
            //    this.show.filter = false;
            //}
            //else if (tab === 'graphs') {
            //    this.show.basicTotals = false;
            //    this.show.budgetTotals = false;
            //    this.show.filter = true;
            //}

            $.event.trigger('run-filter');
        },

        /**
         *
         */
        setTab: function () {
            if (this.env === 'local') {
                this.tab = 'graphs';
            }
            else {
                this.tab = 'transactions';
            }
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
        this.setTab();
        this.getTransactions();
        this.getBudgets();
    }
});