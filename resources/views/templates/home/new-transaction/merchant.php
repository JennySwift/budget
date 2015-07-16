<div class="autocomplete-container">

    <div class="help-row">
        <label>Enter a merchant (optional)</label>

        <div dropdown class="dropdown-directive">
            <button ng-click="showDropdown()" tabindex="-1" class="btn btn-info btn-xs">
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
    </div>

    <input
        ng-model="new_transaction.merchant"
        ng-blur="show.autocomplete.merchant = false"
        ng-keyup="filterTransactions($event.keyCode, new_transaction.merchant, 'merchant')"
        id="new-transaction-merchant"
        class="merchant mousetrap form-control"
        placeholder="merchant"
        type='text'>

    <div ng-cloak ng-show="show.autocomplete.merchant" id="autocomplete-transactions" class="">
        <?php include($templates . '/home/new-transaction/autocomplete.php'); ?>
    </div>

</div>