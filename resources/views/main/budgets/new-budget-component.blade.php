<script id="new-budget-template" type="x-template">

    <div v-show="showNewBudget" class="new-budget">
        <h3>Create a new budget</h3>
        <i v-on:click="showNewBudget = false" class="close fa fa-times"></i>

        <div class="form-group">
            <label for="new-budget-name">Name</label>
            <input
                    v-model="newBudget.name"
                    v-on:keyup.13="insertBudget()"
                    type="text"
                    id="new-budget-name"
                    name="new-budget-name"
                    placeholder="name"
                    class="form-control"
            >
        </div>

        <div class="form-group">
            <label for="new-budget-type">Type</label>

            <select
                    v-model="newBudget.type"
                    v-on:keyup.13="insertBudget()"
                    id="new-budget-type"
                    class="form-control"
            >
                <option
                        v-for="type in types"
                        v-bind:value="type"
                >
                    @{{ type }}
                </option>
            </select>
        </div>

        <div v-if="newBudget.type !== 'unassigned'" class="form-group">
            <label v-if="newBudget.type === 'fixed'" for="new-budget-amount">Amount Per Month</label>
            <label v-if="newBudget.type === 'flex'" for="new-budget-amount">% of Remaining Balance</label>
            <input
                v-model="newBudget.amount"
                v-on:keyup.13="insertBudget()"
                type="text"
                id="new-budget-amount"
                name="new-budget-amount"
                placeholder="amount"
                class="form-control"
            >
        </div>

        <div v-if="newBudget.type !== 'unassigned'" class="form-group">
            <label for="new-budget-starting-date">Starting date</label>
            <input
                v-model="newBudget.startingDate"
                v-on:keyup.13="insertBudget()"
                type="text"
                id="new-budget-starting-date"
                name="new-budget-starting-date"
                placeholder="starting date"
                class="form-control"
            >
        </div>

        <div class="form-group">
            <button
                    v-on:click="insertBudget()"
                    class="btn btn-success"
            >
                Create Budget
            </button>
        </div>

    </div>


</script>
