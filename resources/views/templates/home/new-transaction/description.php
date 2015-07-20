<div class="autocomplete-container">

    <div class="help-row">
        <label>Enter a description (optional)</label>

        <div dropdown-directive class="dropdown-directive">
            <button ng-click="showDropdown()" tabindex="-1" class="btn btn-info btn-xs">
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

    <autocomplete-directive
        dropdown="dropdown.description"
        placeholder="description"
        typing="new_transaction.description"
        newTransaction="new_transaction"
        fnOnEnter="insertTransaction(13)">
    </autocomplete-directive>

<!--    <input-->
<!--        ng-model="new_transaction.description"-->
<!--        ng-blur="show.autocomplete.description = false"-->
<!--        ng-keyup="filterTransactions($event.keyCode, new_transaction.description, 'description')"-->
<!--        id="new-transaction-description"-->
<!--        class="description mousetrap form-control"-->
<!--        placeholder="description"-->
<!--        type='text'>-->
<!---->
<!--    <div ng-cloak ng-show="show.autocomplete.description" id="autocomplete-transactions" class="">-->
<!--        --><?php //include($templates . '/home/new-transaction/autocomplete.php'); ?>
<!--    </div>-->

</div>