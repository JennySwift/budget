<script id="new-budget-template" type="x-template">

    <div v-show="showNewBudget" class="new-entry">
        <h3>Create a new budget</h3>
        <i v-on:click="showNewBudget = false" class="close fa fa-times"></i>

        <div class="flex">

            <div>
                <label>Enter a name</label>

                <input v-model="newBudget.name"
                       v-on:keyup="insertBudget($event.keyCode)"
                       type="text">
            </div>

            <div>
                <label>Select a budget type</label>

                <select v-model="newBudget.type" v-on:keyup="insertBudget($event.keyCode)" class="form-control">
                    <option value="fixed">Fixed</option>
                    <option value="flex">Flex</option>
                    <option value="unassigned">Unassigned</option>
                </select>
            </div>

        </div>

        <div v-if="newBudget.type !== 'unassigned'" class="flex">

            <div>
                <label>Enter an an amount</label>

                <input v-model="newBudget.amount"
                       v-on:keyup="insertBudget($event.keyCode)"
                       type="text">
            </div>

            <div>
                <label>Enter a starting date</label>

                <input
                        v-model="newBudget.starting_date"
                        v-on:keyup="insertBudget($event.keyCode)"
                        type="text">

            </div>

        </div>

        <button
                v-on:click="insertBudget(13)"
                class="btn btn-success">
            Create Budget
        </button>

    </div>


</script>
