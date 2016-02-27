<script id="edit-budget-popup-template" type="x-template">

        <div
                v-show="show.popups.budget"
                v-on:click="closePopup($event, 'budget')"
                id="edit-budget"
                class="popup-outer">

                <div class="popup-inner">
                        <h3>Edit @{{ selectedBudget.name }}</h3>
                        <label>Name</label>
                        <input v-model="selectedBudget.name" type="text">
                        <label>Type (todo)</label>

                        <label v-show="selectedBudget.type !== 'unassigned'">Starting date</label>
                        <input v-show="selectedBudget.type !== 'unassigned'" v-model="selectdBudget.formattedStartingDate" type="text">

                        <label v-show="selectedBudget.type !== 'unassigned'">Amount</label>
                        <input v-show="selectedBudget.type !== 'unassigned'" v-model="selectdBudget.amount" type="text"/>

                        <div class="popup-buttons">
                                <button v-on:click="show.popups.budget = false" class="btn btn-danger">Cancel</button>
                                <button v-on:click="updateBudget()" class="btn btn-success">Save</button>
                        </div>
                </div>

        </div>

</script>
