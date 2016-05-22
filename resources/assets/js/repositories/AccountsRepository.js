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

    /**
    *
    * @param data
    */
    updateAccount: function (data) {
        var index = HelpersRepository.findIndexById(this.state.accounts, data.id);
        this.state.accounts.$set(index, data);
    },

    /**
    *
    * @param account
    */
    deleteAccount: function (account) {
        var index = HelpersRepository.findIndexById(this.state.accounts, account.id);
        this.state.accounts = _.without(this.state.accounts, this.state.accounts[index]);
    }
};