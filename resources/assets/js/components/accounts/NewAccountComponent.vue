<template>
    <div class="flex">

        <div class="form-group">
            <label for="new-account-name">Name</label>
            <input
                v-model="newAccount.name"
                v-on:keyup.13="insertAccount()"
                type="text"
                id="new-account-name"
                name="new-account-name"
                placeholder="name"
                class="form-control"
            >
        </div>

        <div>
            <button v-on:click="insertAccount()" class="btn btn-success">Create</button>
        </div>

    </div>
</template>

<script>
    import helpers from '../../repositories/Helpers'
    export default {
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
                var data = {
                    name: this.newAccount.name
                };

                helpers.post({
                    url: '/api/accounts',
                    data: data,
                    array: 'accounts',
                    message: 'Account created',
                    clearFields: this.clearFields,
                    callback: function () {

                    }.bind(this)
                });
            },
            clearFields () {
                this.newAccount.name = '';
            }
        },
        props: [],
        mounted: function () {

        }
    }
</script>