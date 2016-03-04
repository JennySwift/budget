<div class="autocomplete-container">

    <div class="help-row">
        <label>Enter a merchant (optional)</label>

        <dropdown
                inline-template
                animate-in-class="flipInX"
                animate-out-class="flipOutX"
                class="dropdown-directive"
        >

            <div>

                <button
                        v-on:click="toggleDropdown()"
                        tabindex="-1"
                        class="btn btn-info btn-xs"
                >
                    Help
                    <span class="caret"></span>
                </button>

                <div class="dropdown-content animated">
                    <div class="help">
                        <div>As you type a merchant, previous transactions with a matching merchant will show up.</div>
                        <div>To fill in the fields with those of a previous transaction, either:</div>
                        <ol>
                            <li>Use the up and down arrow keys to select a previous transaction, then press enter.</li>
                            <li>Click on one of the previous transactions.</li>
                        </ol>
                        <div>Alternatively, you can just keep typing if you don't want to use the autocomplete.</div>
                    </div>
                </div>

            </div>

        </dropdown>
    </div>

    <transaction-autocomplete
        placeholder="merchant"
        id="new-transaction-merchant"
        :typing.sync="newTransaction.merchant"
        :new-transaction.sync="newTransaction"
        :function-on-enter="insertTransaction"
    >
    </transaction-autocomplete>

</div>