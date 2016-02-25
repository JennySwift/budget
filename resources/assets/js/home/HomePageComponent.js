var HomePage = Vue.component('home-page', {
    template: '#home-page-template',
    data: function () {
        return {
            page: 'home',
            budgets: budgets,
            colors: me.preferences.colors,
            transactions: transactions,
            tab: '',
            show: {}
        };
    },
    components: {},
    methods: {
        toggleFilter: function () {
            this.show.filter = !this.show.filter;
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
            if (env === 'local') {
                this.tab = 'transactions';
            }
            else {
                this.tab = 'transactions';
            }
        }
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        this.setTab();
    }
});