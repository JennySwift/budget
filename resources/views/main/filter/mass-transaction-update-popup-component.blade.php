<script id="mass-transaction-update-popup-template" type="x-template">

<div
    v-show="showPopup"
    v-on:click="closePopup($event)"
    class="popup-outer"
>

    <div id="mass-transaction-update-popup" class="popup-inner">

        <div class="messages">
            <div v-if="!showProgress">
                <div>Clicking 'Go' will add the chosen budgets to the @{{ transactions.length }} transactions that you can see on the page.</div>
                <div>No duplicate budgets will be added to your transactions.</div>
                <div>The allocation given to the budgets will be 100% of the transaction. So you may need to reallocate the transactions after doing this.</div>
            </div>
            <h5 v-if="count < transactions.length && showProgress">Updating @{{ transactions.length }} transactions</h5>
            <h5 v-if="count === transactions.length">Done! The selected budgets have been added to @{{ transactions.length }} transactions.</h5>
        </div>

        <div v-show="showProgress" class="progress">
            <div
                class="progress-bar progress-bar-success progress-bar-striped"
                role="progressbar"
                {{--aria-valuenow="40"--}}
                aria-valuemin="0"
                aria-valuemax="100"
                v-bind:style="{width: progressWidth + '%'}"
            >

                @{{ count }}

            </div>
        </div>

        Add budgets to transactions

        <budget-autocomplete
                :chosen-budgets.sync="budgetsToAdd"
                :budgets="budgets"
                multiple-budgets="true"
        >
        </budget-autocomplete>

        <div class="buttons">
            <button v-on:click="showPopup = false" class="btn btn-default">Close</button>
            <button v-on:click="addBudgetsToTransactions()" class="btn btn-success">Go</button>
        </div>

    </div>
</div>

</script>