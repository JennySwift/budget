var AccountsFilter = Vue.component('accounts-filter', {
    template: '#accounts-filter-template',
    data: function () {
        return {
            showContent: false,
            accounts: []
        };
    },
    components: {},
    methods: {

        /**
        *
        */
        getAccounts: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/accounts', function (response) {
                this.accounts = response;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        }
    },
    props: [
        'filter',
        'filterTab',
        'runFilter',
    ],
    ready: function () {
        this.getAccounts();
    }
});