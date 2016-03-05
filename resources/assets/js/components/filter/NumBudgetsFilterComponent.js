var NumBudgetsFilter = Vue.component('num-budgets-filter', {
    template: '#num-budgets-filter-template',
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