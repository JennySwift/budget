var AccountsPage = Vue.component('accounts-page', {
    template: '#accounts-page-template',
    data: function () {
        return {
            accounts: [],
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
        },

        /**
         *
         * @param account
         */
        showEditAccountPopup: function (account) {
            $.event.trigger('show-edit-account-popup', [account]);
        },
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        this.getAccounts();
    }
});