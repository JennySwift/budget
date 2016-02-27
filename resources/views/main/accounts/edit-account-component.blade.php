<script id="edit-account-template" type="x-template">

    <div
            v-show="showPopup"
            v-on:click="closePopup($event)"
            id="edit-account-name"
            class="popup-outer">

        <div class="popup-inner">
            <label>Enter a new name for your account</label>
            <input v-model="selectedAccount.name" type="text">

            <div class="popup-buttons">
                <button v-on:click="showPopup = false" class="btn btn-danger">Cancel</button>
                <button v-on:click="updateAccount()" class="btn btn-success">Save</button>
            </div>

        </div>

    </div>

</script>
