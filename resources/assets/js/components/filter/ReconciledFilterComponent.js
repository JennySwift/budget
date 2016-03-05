var ReconciledFilter = Vue.component('reconciled-filter', {
    template: '#reconciled-filter-template',
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
        'runFilter'
    ],
    ready: function () {

    }
});