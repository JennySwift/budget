
<div ng-show="show.newBudget" class="new-entry">
    <h3>Create a new budget</h3>
    <i ng-click="show.newBudget = false" class="close fa fa-times"></i>

    <div class="flex">

        <div>
            <label>Enter a name</label>

            <input ng-model="newBudget.name"
                   ng-keyup="insertBudget($event.keyCode)"
                   type="text">
        </div>

        <div>
            <label>Select a budget type</label>

            <select ng-model="newBudget.type" ng-keyup="insertBudget($event.keyCode)" class="form-control">
                <option value="fixed">Fixed</option>
                <option value="flex">Flex</option>
                <option value="none">None</option>
            </select>
        </div>

    </div>

    <div class="flex">

        <div>
            <label>Enter an an amount</label>

            <input ng-model="newBudget.amount"
                   ng-keyup="insertBudget($event.keyCode)"
                   type="text">
        </div>

        <div>
            <label>Enter a starting date (optional)</label>

            <input ng-model="newBudget.starting_date"
                   ng-keyup="insertBudget($event.keyCode)"
                   type="text">
        </div>

    </div>

    <button
        ng-click="insertBudget(13)"
        class="btn btn-success">
        Create Budget
    </button>

</div>
