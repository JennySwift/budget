
<div
    v-show="show.popups.budget"
    v-on:click="closePopup($event, 'budget')"
    id="edit-budget"
    class="popup-outer">

	<div class="popup-inner">
        <h3>Edit [[budget_popup.name]]</h3>
        <label>Name</label>
        <input v-model="budget_popup.name" type="text">
        <label>Type (todo)</label>

        <label v-show="budget_popup.type !== 'unassigned'">Starting date</label>
        <input v-show="budget_popup.type !== 'unassigned'" v-model="budget_popup.formattedStartingDate" type="text">

        <label v-show="budget_popup.type !== 'unassigned'">Amount</label>
        <input v-show="budget_popup.type !== 'unassigned'" v-model="budget_popup.amount" type="text"/>

        <div class="popup-buttons">
            <button v-on:click="show.popups.budget = false" class="btn btn-danger">Cancel</button>
            <button v-on:click="updateBudget()" class="btn btn-success">Save</button>
        </div>
	</div>

</div>