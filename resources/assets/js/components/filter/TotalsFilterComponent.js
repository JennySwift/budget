var TotalsFilter = Vue.component('totals-filter', {
    template: '#totals-filter-template',
    data: function () {
        return {
            showContent: false
        };
    },
    components: {},
    methods: {

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