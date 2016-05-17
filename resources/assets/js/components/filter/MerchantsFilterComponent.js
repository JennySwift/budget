var MerchantsFilter = Vue.component('merchants-filter', {
    template: '#merchants-filter-template',
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
