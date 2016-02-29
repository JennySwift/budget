var EditAccount = Vue.component('edit-account', {
    template: '#edit-account-template',
    data: function () {
        return {
            selectedAccount: {},
            showPopup: false
        };
    },
    components: {},
    methods: {

        /**
        *
        */
        updateAccount: function (account) {
            $.event.trigger('show-loading');

            var data = {
                name: this.selectedAccount.name
            };

            this.$http.put('/api/accounts/' + this.selectedAccount.id, data, function (response) {
                var index = _.indexOf(this.accounts, _.findWhere(this.accounts, {id: this.selectedAccount.id}));
                this.accounts[index] = response;
                this.showPopup = false;
                $.event.trigger('provide-feedback', ['Account updated', 'success']);
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        deleteAccount: function () {
            if (confirm("Are you sure?")) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/accounts/' + this.selectedAccount.id, function (response) {
                        this.accounts = _.without(this.accounts, this.selectedAccount);
                        //var index = _.indexOf(this.accounts, _.findWhere(this.accounts, {id: this.account.id}));
                        //this.accounts = _.without(this.accounts, this.accounts[index]);
                        this.showPopup = false;
                        $.event.trigger('provide-feedback', ['Account deleted', 'success']);
                        $.event.trigger('hide-loading');
                    })
                    .error(function (response) {
                        HelpersRepository.handleResponseError(response);
                    });
            }
        },

        /**
         *
         */
        closePopup: function ($event) {
            HelpersRepository.closePopup($event, this);
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('show-edit-account-popup', function (event, account) {
                that.selectedAccount = account;
                that.showPopup = true;
            });
        }
    },
    props: [
        'accounts'
    ],
    ready: function () {
        this.listen();
    }
});
