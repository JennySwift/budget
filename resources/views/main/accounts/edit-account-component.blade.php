<script id="edit-account-template" type="x-template">

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

</script>
