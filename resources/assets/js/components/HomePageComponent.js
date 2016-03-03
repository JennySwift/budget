var HomePage = Vue.component('home-page', {
    template: '#home-page-template',
    data: function () {
        return {
            page: 'home',
            budgets: {},
            transactions: [],
            colors: {},
            tab: 'transactions',
            show: ShowRepository.defaults,
            env: ''
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

        transactionsTab: function () {
            this.tab = 'transactions';
            this.show.basic_totals = true;
            this.show.budget_totals = true;
            this.show.filter = false;
            $.event.trigger('run-filter');
        },

        graphsTab: function () {
            this.tab = 'graphs';
            this.show.basic_totals = false;
            this.show.budget_totals = false;
            this.show.filter = true;
            $.event.trigger('run-filter');
        },

        setTab: function () {
            if (this.env === 'local') {
                this.tab = 'transactions';
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
        //data to be received from parent
    ],
    ready: function () {
        this.setTab();
        this.getTransactions();
    }
});