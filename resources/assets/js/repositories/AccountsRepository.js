var AccountsRepository = {
    state: {
        accounts: []
    },

    /**
     *
     */
    getAccounts: function (that) {
        $.event.trigger('show-loading');
        that.$http.get('/api/accounts?includeBalance=true', function (response) {
            AccountsRepository.state.accounts = response;
            $.event.trigger('accounts-loaded');
            $.event.trigger('hide-loading');
        })
        .error(function (response) {
            HelpersRepository.handleResponseError(response);
        });
    },
};