
<div class="new-entry">
    <label>Add a flex budget to one of your tags</label>

    <tag-autocomplete-directive
            dropdown="new_flex_budget.dropdown"
            tags="tags"
            fnOnEnter="filterTags(13)"
            multipleTags="false"
            model="new_FLB"
            id="new-flex-budget-name"
            focusOnEnter="budget-flex-budget-input">
    </tag-autocomplete-directive>

    <input ng-model="new_FLB.budget"
           ng-keyup="updateBudget($event.keyCode, new_FLB, 'flex')"
           id="budget-flex-budget-input"
           type="text">
</div>

