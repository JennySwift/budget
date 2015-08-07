
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
            focusOnEnter="budget-flex-budget-input">
    </tag-autocomplete-directive>

    <label>Enter an an amount for your tag</label>

    <input ng-model="new_FLB.budget"
           ng-keyup="updateBudget($event.keyCode, new_FLB, 'flex')"
           id="budget-flex-budget-input"
           type="text">
</div>

