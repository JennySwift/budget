var InvalidAllocationFilter = Vue.component('invalid-allocation-filter', {
    template: '#invalid-allocation-filter-template',
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