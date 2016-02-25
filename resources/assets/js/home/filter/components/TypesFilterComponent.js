var TypesFilter = Vue.component('types-filter', {
    template: '#types-filter-template',
    data: function () {
        return {
            types: ["income", "expense", "transfer"]
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


