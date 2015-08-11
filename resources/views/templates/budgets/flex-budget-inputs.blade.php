
<div class="new-entry">
    <h3>Add a new flex budget</h3>

    <label>Select a tag</label>

    <tag-autocomplete-directive
            dropdown="new_flex_budget.dropdown"
            tags="tags"
            fnOnEnter="filterTags(13)"
            multipleTags="false"
            model="new_FLB"
            id="new-flex-budget-name"
            focusOnEnter="new-flex-budget-amount">
    </tag-autocomplete-directive>

    <label>Enter an an amount for your tag</label>

    <input ng-model="new_FLB.budget"
           ng-keyup="createBudget($event.keyCode, new_FLB, 'flex')"
           id="new-flex-budget-amount"
           type="text">

    <label>Enter a starting date (optional)</label>

    <input ng-model="new_FLB.starting_date"
           ng-keyup="createBudget($event.keyCode, new_FLB, 'flex')"
           id="new-flex-budget-SD"
           type="text">

    <button
            ng-click="createBudget(13, new_FB, 'flex')"
            class="btn btn-success">
        Create Budget
    </button>

</div>

