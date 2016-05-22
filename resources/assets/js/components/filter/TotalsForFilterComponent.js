var TotalsForFilter = Vue.component('totals-for-filter', {
    template: '#totals-for-filter-template',
    data: function () {
        return {

        };
    },
    components: {},
    filters: {
        numberFilter: function (number, howManyDecimals) {
            return HelpersRepository.numberFilter(number, howManyDecimals);
        }
    },
    methods: {

    },
    props: [
        'show',
        'filter',
        'filterTotals'
    ],
    ready: function () {

    }
});