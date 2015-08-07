
<div class="new-entry">
    <label>Add a fixed budget to one of your tags</label>

    {{--I'm baffled as to why, if I use model=new_fixed_budget it is buggy.--}}
    {{--After a tag is chosen, if the user then hits backspace it edits the tag in the dropdown to that new name in the input field.--}}

    <tag-autocomplete-directive
            dropdown="new_fixed_budget.dropdown"
            tags="tags"
            fnOnEnter="filterTags(13)"
            multipleTags="false"
            model="new_FB"
            modelName="new_fixed_budget.name"
            id="new-fixed-budget-name"
            focusOnEnter="budget-fixed-budget-input">
    </tag-autocomplete-directive>

    <input ng-model="new_FB.budget"
           ng-keyup="updateBudget($event.keyCode, new_FB, 'fixed')"
           id="budget-fixed-budget-input"
           type="text">
</div>
