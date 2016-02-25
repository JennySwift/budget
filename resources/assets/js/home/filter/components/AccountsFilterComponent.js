var AccountsFilter = Vue.component('accounts-filter', {
    template: '#accounts-filter-template',
    data: function () {
        return {
            accounts: []
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