var FixedBudgetsPage = Vue.component('fixed-budgets-page', {
    template: '#fixed-budgets-page-template',
    data: function () {
        return {
            fixedBudgetTotals: [],
            show: ShowRepository.defaults,
            fixedBudgets: []
        };
    },
    components: {},
    filters: {
        /**
         *
         * @param number
         * @param howManyDecimals
         * @returns {Number}
         */
        numberFilter: function (number, howManyDecimals) {
            return HelpersRepository.numberFilter(number, howManyDecimals);
        }
    },
    methods: {
        /**
         *
         */
        getFixedBudgets: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/budgets?fixed=true', function (response) {
                    this.fixedBudgets = response;
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    HelpersRepository.handleResponseError(response);
                });
        },

        /**
        *
        */
        getFixedBudgetTotals: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/totals/fixedBudget', function (response) {
                this.fixedBudgetTotals = response;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        }
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        this.getFixedBudgets();
        this.getFixedBudgetTotals();
    }
});
