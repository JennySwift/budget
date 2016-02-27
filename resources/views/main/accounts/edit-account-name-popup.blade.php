
<div
    v-show="show.popups.edit_account"
    v-on:click="closePopup($event, 'edit_account')"
    id="edit-account-name"
    class="popup-outer">

    <div class="popup-inner">
        <label>Enter a new name for your account</label>
        <input v-model="edit_account_popup.name" type="text">

        <div class="popup-buttons">
            <button v-on:click="show.popups.edit_account = false" class="btn btn-danger">Cancel</button>
            <button v-on:click="updateAccount()" class="btn btn-success">Save</button>
        </div>

    </div>

</div>