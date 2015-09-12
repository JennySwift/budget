
<div class="new-entry">
    <h3>Create a new budget</h3>
    <label>Enter a name</label>

    <input ng-model="newBudget.name"
           ng-keyup="insertBudget($event.keyCode)"
           type="text">

    <label>Select a budget type</label>

    <select ng-model="newBudget.type" ng-keyup="insertBudget($event.keyCode)" class="form-control">
        <option value="fixed">Fixed</option>
        <option value="flex">Flex</option>
        <option value="none">None</option>
    </select>

    <label>Enter an an amount</label>

    <input ng-model="newBudget.amount"
           ng-keyup="insertBudget($event.keyCode)"
           type="text">

    <label>Enter a starting date (optional)</label>

    <input ng-model="newBudget.starting_date"
           ng-keyup="insertBudget($event.keyCode)"
           type="text">

    <button
        ng-click="insertBudget(13)"
        class="btn btn-success">
        Create Budget
    </button>

</div>
