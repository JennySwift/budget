<div ng-show="show.popups.edit_account" ng-click="closePopup($event, 'edit_account')" class="popup-outer">
    <div class="popup-inner">
        <input ng-model="edit_account.name" type="text">
        <button ng-click="updateAccount()">done</button>
    </div>
</div>