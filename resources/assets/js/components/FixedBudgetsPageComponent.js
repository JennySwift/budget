var FixedBudgetsPage = Vue.component('fixed-budgets-page', {
    template: '#fixed-budgets-page-template',
    data: function () {
        return {
            fixedBudgetTotals: [],
            show: ShowRepository.defaults,
        };
    },
    components: {},
    methods: {

    },
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
    props: [
        //data to be received from parent
    ],
    ready: function () {

    }
});
