var TotalsFilter = Vue.component('totals-filter', {
    template: '#totals-filter-template',
    data: function () {
        return {
            showContent: false
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        filterTotal: function () {
            $.event.trigger('run-filter');
        },

        /**
         * type is either 'in' or 'out'
         *
         * @DO:
         * This method is duplicated in other parts of the filter, but
         * for some reason when I had it in my FilterController, both
         * parameters were undefined.
         *
         * @param field
         * @param type
         */
        clearFilterField: function (field, type) {
            this.filter[field][type] = "";
            $.event.trigger('run-filter');
        },

    },
    props: [
        'filter',
        'filterTab',
        'runFilter'
    ],
    ready: function () {

    }
});