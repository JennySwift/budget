<div class="autocomplete-container">

    <div class="help-row">
        <label>Enter a description (optional)</label>

        <div dropdowns-directive class="dropdown-directive">
            <button ng-click="toggleDropdown()" tabindex="-1" class="btn btn-info btn-xs">
                Help
                <span class="caret"></span>
            </button>

            <div class="dropdown-content animated">
                <div class="help">
                    <div>As you type a description, previous transactions with a matching description will show up.</div>
                    <div>To fill in the fields with those of a previous transaction, either:</div>
                    <ol>
                        <li>Use the up and down arrow keys to select a previous transaction, then press enter.</li>
                        <li>Click on one of the previous transactions.</li>
                    </ol>
                    <div>Alternatively, you can just keep typing if you don't want to use the autocomplete.</div>
                </div>
            </div>
        </div>
    </div>

    <transaction-autocomplete-directive
        dropdown="dropdown.description"
        placeholder="description"
        typing="new_transaction.description"
        newTransaction="new_transaction"
        fnOnEnter="insertTransaction(13)"
        showLoading="showLoading()"
        hideLoading="hideLoading()"
        loading="loading">
    </transaction-autocomplete-directive>

</div>