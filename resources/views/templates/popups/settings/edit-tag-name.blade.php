<div
    ng-show="show.popups.edit_tag"
    ng-click="closePopup($event, 'edit_tag')"
    id="edit-tag-name"
    class="popup-outer">

    <div class="popup-inner">

        <input ng-model="edit_tag_popup.name" type="text">

        <div class="popup-buttons">
            <button ng-click="show.popups.edit_tag = false" class="btn btn-danger">Cancel</button>
            <button ng-click="updateTag()" class="btn btn-success">Save</button>
        </div>

    </div>
</div>