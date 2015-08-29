
<div class="new-entry">
    <h3>Add a new fixed budget</h3>
    <label>Enter a name</label>

    {{--I'm baffled as to why, if I use model=new_fixed_budget it is buggy.--}}
    {{--After a tag is chosen, if the user then hits backspace it edits the tag in the dropdown to that new name in the input field.--}}

    {{--<tag-autocomplete-directive--}}
            {{--dropdown="new_fixed_budget.dropdown"--}}
            {{--tags="tags"--}}
            {{--fnOnEnter="filterTags(13)"--}}
            {{--multipleTags="false"--}}
            {{--model="new_FB"--}}
            {{--modelName="new_fixed_budget.name"--}}
            {{--id="new-fixed-budget-name"--}}
            {{--focusOnEnter="new-fixed-budget-amount">--}}
    {{--</tag-autocomplete-directive>--}}
    <input ng-model="new_FB.name"
           ng-keyup="createBudget($event.keyCode, new_FB, 'fixed')"
           id="new-fixed-budget-amount"
           type="text">

    <label>Enter an an amount for your budget</label>

    <input ng-model="new_FB.amount"
           ng-keyup="createBudget($event.keyCode, new_FB, 'fixed')"
           id="new-fixed-budget-amount"
           type="text">

    <label>Enter a starting date (optional)</label>

    <input ng-model="new_FB.starting_date"
           ng-keyup="createBudget($event.keyCode, new_FB, 'fixed')"
           id="new-fixed-budget-SD"
           type="text">

    <button
        ng-click="createBudget(13, new_FB, 'fixed')"
        class="btn btn-success">
        Create Budget
    </button>

</div>
