<template>
    <div
        v-show="showPopup"
        v-on:click="closePopup($event)"
        id="edit-account-name"
        class="popup-outer">

        <div class="popup-inner">
            <h3>Edit @{{ selectedAccount.name }}</h3>

            <div class="form-group">
                <label for="edit-account-name">Name</label>
                <input
                    v-model="selectedAccount.name"
                    type="text"
                    id="edit-account-name"
                    name="edit-account-name"
                    placeholder="name"
                    class="form-control"
                >
            </div>

            <div class="buttons">
                <button v-on:click="showPopup = false" class="btn btn-default">Cancel</button>
                <button v-on:click="updateAccount()" class="btn btn-success">Save</button>
                <button v-on:click="deleteAccount(account)" class="btn btn-danger btn-sm">Delete</button>
            </div>

        </div>

    </div>
</template>

<script>
    export default {
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
                    AccountsRepository.updateAccount(response);
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
                        AccountsRepository.deleteAccount(this.selectedAccount);
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
        mounted: function () {
            this.listen();
        }
    }
</script>
