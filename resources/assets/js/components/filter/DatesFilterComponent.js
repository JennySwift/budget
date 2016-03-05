var DatesFilter = Vue.component('dates-filter', {
    template: '#dates-filter-template',
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
        filterDate: function () {
            $.event.trigger('run-filter');
        },

        /**
         * type is either 'in' or 'out'
         * @param field
         * @param type
         */
        clearDateField: function (field, type) {
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