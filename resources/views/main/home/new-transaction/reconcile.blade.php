<div class="reconcile">

    <div class="help-row">
        <label for="new-transaction-reconciled">Check this box if your transaction is reconciled</label>

        <div dropdowns-directive class="dropdown-directive">
            <button v-on:click="toggleDropdown()" tabindex="-1" class="btn btn-info btn-xs">
                Help
                <span class="caret"></span>
            </button>

            <div class="dropdown-content animated">
                <div class="help">
                    <div>The reconciled checkbox is so you can compare the transactions you enter here with those on your bank statement, verifying them, keeping them in sync.</div>
                    <div>You can use the filter (click the magnifying glass icon at the top of the page) to see transactions that are/are not reconciled.</div>
                    <div>If you don't reconcile the transaction now, you can reconcile it later by checking the reconciled checkbox for the transaction in the table below.</div>
                </div>
            </div>
        </div>
    </div>

    <input v-model="newTransaction.reconciled" id="new-transaction-reconciled" type="checkbox">

    {{--<checkbox--}}
        {{--model="new_transaction.reconciled"--}}
        {{--id="new-transaction-reconciled">--}}
    {{--</checkbox>--}}

</div>