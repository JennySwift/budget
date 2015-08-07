
<div
    ng-show="show.popups.edit_CSD"
    ng-click="closePopup($event, 'edit_CSD')"
    id="edit-CSD"
    class="popup-outer">

	<div class="popup-inner">
        <label>Enter a date to start budgeting for this tag</label>
        <input ng-model="edit_CSD_popup.formatted_starting_date" type="text">

        <div class="popup-buttons">
            <button ng-click="show.popups.edit_CSD = false" class="btn btn-danger">Cancel</button>
            <button ng-click="updateCSD()" class="btn btn-success">Save</button>
        </div>
	</div>

</div>