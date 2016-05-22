<script id="new-account-template" type="x-template">

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

</script>