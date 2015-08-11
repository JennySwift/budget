
<div
    ng-show="show.popups.budget"
    ng-click="closePopup($event, 'budget')"
    id="edit-budget"
    class="popup-outer">

	<div class="popup-inner">
        <label>Enter a date to start budgeting for this tag</label>
        <input ng-model="budget_popup.formatted_starting_date" type="text">

        <label>Enter a new budget for this tag</label>
        <input ng-model="budget_popup[budget_popup.type + '_budget']" type="text"/>

        <div class="popup-buttons">
            <button ng-click="show.popups.budget = false" class="btn btn-danger">Cancel</button>
            <button ng-click="updateBudget()" class="btn btn-success">Save</button>
        </div>
	</div>

</div>