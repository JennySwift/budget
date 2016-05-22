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
            FilterRepository.resetOffset();
            this.runFilter();
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
