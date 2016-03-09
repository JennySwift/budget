<script id="mass-transaction-update-popup-template" type="x-template">

<div
    v-show="showPopup"
    v-on:click="closePopup($event)"
    class="popup-outer"
>

    <div id="mass-transaction-update-popup" class="popup-inner">

        Add budgets to transactions

        <budget-autocomplete
                :chosen-budgets.sync="budgetsToAdd"
                :budgets="budgets"
                multiple-budgets="true"
        >
        </budget-autocomplete>

        <div class="buttons">
            <button v-on:click="showPopup = false" class="btn btn-default">Cancel</button>
            <button v-on:click="addBudgetsToTransactions()" class="btn btn-success">Go</button>
        </div>

    </div>
</div>

</script>