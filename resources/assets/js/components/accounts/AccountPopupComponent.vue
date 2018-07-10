<template>
    <popup
        popup-name="account"
        id="account-popup"
        :save="updateAccount"
        :destroy="deleteAccount"
        :redirect-to="redirectTo"
    >
        <div slot="content">
            <h3>Edit {{ shared.selectedAccount.name }}</h3>

            <div class="form-group">
                <label for="edit-account-name">Name</label>
                <input
                    v-model="shared.selectedAccount.name"
                    type="text"
                    id="edit-account-name"
                    name="edit-account-name"
                    placeholder="name"
                    class="form-control"
                >
            </div>
        </div>

    </popup>

</template>

<script>
    import helpers from '../../repositories/helpers/Helpers'
    export default {
        data: function () {
            return {
                shared: store.state,
                redirectTo: false
            };
        },
        components: {},
        methods: {

            /**
             *
             */
            updateAccount: function () {
                var data = {
                    name: this.shared.selectedAccount.name
                };

                helpers.put({
                    url: '/api/accounts/' + this.shared.selectedAccount.id,
                    data: data,
                    property: 'accounts',
                    message: 'Account updated',
                    redirectTo: this.redirectTo,
                });
            },

            /**
            *
            */
            deleteAccount: function () {
                helpers.delete({
                    url: '/api/accounts/' + this.shared.selectedAccount.id,
                    array: 'accounts',
                    itemToDelete: this.shared.selectedAccount,
                    message: 'Account deleted',
                    redirectTo: this.redirectTo
                });
            },
        },
        props: [],
        mounted: function () {

        }
    }
</script>
