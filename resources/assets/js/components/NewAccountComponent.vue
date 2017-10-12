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
        mounted: function () {

        }
    }
</script>