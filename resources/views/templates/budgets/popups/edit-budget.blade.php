
<div
    ng-show="show.popups.budget"
    ng-click="closePopup($event, 'budget')"
    id="edit-budget"
    class="popup-outer">

	<div class="popup-inner">
        <h3>Edit [[budget_popup.name]]</h3>
        <label>Name</label>
        <input ng-model="budget_popup.name" type="text">
        <label>Type (todo)</label>
        <label>Starting date</label>
        <input ng-model="budget_popup.formattedStartingDate" type="text">

        <label>Amount</label>
        <input ng-model="budget_popup.amount" type="text"/>

        <div class="popup-buttons">
            <button ng-click="show.popups.budget = false" class="btn btn-danger">Cancel</button>
            <button ng-click="updateBudget()" class="btn btn-success">Save</button>
        </div>
	</div>

</div>