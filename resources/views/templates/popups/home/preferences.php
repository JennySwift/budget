
<div ng-show="show.popups.preferences" ng-click="closePopup($event, 'preferences')" id="" class="popup-outer">

    <div class="popup-inner">

        <h1>preferences</h1>

        <label>Clear fields upon entering new transaction</label>

        <checkbox
            model="me.settings.clear_fields"
            element="clear_fields">
        </checkbox>

        <button ng-click="show.popups.preferences = false" class="close-popup btn btn-sm">close</button>
        <button ng-click="savePreferences()" class="btn btn-success">Save</button>

    </div>

</div>