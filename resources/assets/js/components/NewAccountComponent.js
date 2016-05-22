var NewAccount = Vue.component('new-account', {
    template: '#new-account-template',
    data: function () {
        return {
            newAccount: {}
        };
    },
    components: {},
    methods: {
        /**
         *
         */
        insertAccount: function () {
            $.event.trigger('show-loading');
            var data = {
                name: this.newAccount.name
            };

            this.$http.post('/api/accounts', data, function (response) {
                    this.accounts.push(response);
                    this.newAccount.name = '';
                    $.event.trigger('provide-feedback', ['Account created', 'success']);
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    HelpersRepository.handleResponseError(response);
                });
        },
    },
    props: [
        'accounts'
    ],
    ready: function () {

    }
});
