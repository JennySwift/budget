var AccountsFilter = Vue.component('accounts-filter', {
    template: '#accounts-filter-template',
    data: function () {
        return {
            showContent: false,
            accountsRepository: AccountsRepository.state
        };
    },
    components: {},
    methods: {

    },
    props: [
        'filter',
        'filterTab',
        'runFilter',
    ],
    ready: function () {

    }
});