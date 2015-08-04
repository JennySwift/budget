
<div ng-show="show.popups.edit_CSD" ng-click="closePopup($event, 'edit_CSD')" class="popup-outer">

	<div class="popup-inner">
        <input ng-model="edit_CSD.starting_date" type="text">
        <button ng-click="updateCSD()">done</button>
	</div>

</div>