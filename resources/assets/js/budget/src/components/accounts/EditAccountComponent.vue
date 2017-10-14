<template>
    <div
        v-show="showPopup"
        v-on:click="closePopup($event)"
        id="edit-account-name"
        class="popup-outer">

        <div class="popup-inner">
            <h3>Edit {{ selectedAccount.name }}</h3>

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
    import helpers from '../../repositories/Helpers'
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
                var data = {
                    name: this.selectedAccount.name
                };

                helpers.put({
                    url: '/api/accounts/' + this.selectedAccount.id,
                    data: data,
                    property: 'accounts',
                    message: 'Account updated',
                    redirectTo: this.redirectTo,
                    callback: function (response) {
                        store.updateAccount(response);
                    }.bind(this)
                });
            },

            /**
            *
            */
            deleteAccount: function () {
                helpers.delete({
                    url: '/api/accounts/' + this.selectedAccount.id,
                    array: 'accounts',
                    itemToDelete: this.account,
                    message: 'Account deleted',
                    redirectTo: this.redirectTo,
                    callback: function () {
                        this.showPopup = false;
                        store.deleteAccount(this.selectedAccount);
                    }.bind(this)
                });
            },

            /**
             *
             */
            closePopup: function ($event) {
                helpers.closePopup($event, this);
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
        props: [],
        mounted: function () {
            this.listen();
        }
    }
</script>
