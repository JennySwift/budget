var DescriptionsFilter = Vue.component('descriptions-filter', {
    template: '#descriptions-filter-template',
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
        filterDescriptionOrMerchant: function () {
            this.resetOffset();
            $.event.trigger('run-filter');
        },

        /**
         *
         */
        resetOffset: function () {
            this.filter.offset = 0;
        },

        /**
         * $type is either 'in' or 'out'
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
