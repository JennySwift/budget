var Totals = Vue.component('totals', {
    template: '#totals-template',
    data: function () {
        return {
            totalsRepository: TotalsRepository.state,
            totalsLoading: false,
            me: me
        };
    },
    components: {},
    computed: {
        sideBarTotals: function () {
          return this.totalsRepository.sideBarTotals;
        },
        totalChanges: function () {
            return this.totalsRepository.totalChanges;
        }
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
    methods: {


    },
    props: [
        'show'
    ],
    ready: function () {

    }
});
