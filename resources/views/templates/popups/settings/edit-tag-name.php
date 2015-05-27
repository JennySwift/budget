<div ng-show="show.popups.edit_tag" ng-click="closePopup($event, 'edit_tag')" class="popup-outer">
    <div class="popup-inner">
        <input ng-model="edit_tag.name" type="text">
        <button ng-click="updateTag()">done</button>
    </div>
</div>