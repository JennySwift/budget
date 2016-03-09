<script id="update-favourite-transaction-template" type="x-template">

    <div
            v-show="showPopup"
            v-on:click="closePopup($event)"
            class="popup-outer"
    >

        <div id="update-favourite-transaction-popup" class="popup-inner">

            <div class="form-group">
                <label for="selected-favourite-name">Name</label>
                <input
                    v-model="selectedFavourite.name"
                    type="text"
                    id="selected-favourite-name"
                    name="selected-favourite-name"
                    placeholder="name"
                    class="form-control"
                >
            </div>

            <div class="buttons">
                <button v-on:click="showPopup = false" class="btn btn-default">Cancel</button>
                <button v-on:click="deleteFavouriteTransaction()" class="btn btn-danger">Delete</button>
                <button v-on:click="updateFavouriteTransaction()" class="btn btn-success">Save</button>
            </div>

        </div>
    </div>

</script>