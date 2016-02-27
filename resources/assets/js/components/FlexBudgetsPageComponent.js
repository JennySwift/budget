var FlexBudgetsPage = Vue.component('flex-budgets-page', {
    template: '#flex-budgets-page-template',
    data: function () {
        return {
            show: ShowRepository.defaults,
            flexBudgets: [],
            flexBudgetTotals: []
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
        getFlexBudgets: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/budgets?flex=true', function (response) {
                    this.flexBudgets = response;
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    HelpersRepository.handleResponseError(response);
                });
        },

        /**
         *
         */
        getFlexBudgetTotals: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/totals/flexBudget', function (response) {
                    this.flexBudgetTotals = response;
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
        this.getFlexBudgets();
        this.getFlexBudgetTotals();
    }

});
