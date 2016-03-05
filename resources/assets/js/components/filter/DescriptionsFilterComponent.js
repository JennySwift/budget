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
            this.runFilter();
        },

        /**
         *
         */
        resetOffset: function () {
            this.filter.offset = 0;
        },

    },
    props: [
        'filter',
        'filterTab',
        'runFilter',
        'clearFilterField'
    ],
    ready: function () {

    }
});
