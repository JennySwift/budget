
<!-- This div is so the is has same margin as other -->
<div ng-show="show.new_transaction" ng-style="{color: colors[new_transaction.type]}" id="new-transaction">

    <div>
        <?php include($templates . '/home/new-transaction/date-help.php'); ?>

        <input
            ng-value="new_transaction.date.entered"
            ng-keyup="insertTransaction($event.keyCode)"
            id="date"
            placeholder="date"
            type='text'
            class="date mousetrap form-control">
    </div>

    <div class="autocomplete-container">

        <div class="help-row">
            <label>Enter a description (optional)</label>

            <div dropdown class="dropdown-directive">
                <button ng-click="showDropdown()" class="btn btn-info btn-xs">
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

        <input
            ng-model="new_transaction.description"
            ng-blur="show.autocomplete.description = false"
            ng-keyup="filterTransactions($event.keyCode, new_transaction.description, 'description')"
            id="new-transaction-description"
            class="description mousetrap form-control"
            placeholder="description"
            type='text'>

        <div ng-cloak ng-show="show.autocomplete.description" id="autocomplete-transactions" class="">
            <?php include($templates . '/home/new-transaction/autocomplete.php'); ?>
        </div>

    </div>

    <div class="autocomplete-container">

        <div class="help-row">
            <label>Enter a merchant (optional)</label>

            <div dropdown class="dropdown-directive">
                <button ng-click="showDropdown()" class="btn btn-info btn-xs">
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

    <div>
        <div class="help-row">
            <label>Enter the total</label>

            <div dropdown class="dropdown-directive">
                <button ng-click="showDropdown()" class="btn btn-info btn-xs">
                    Help
                    <span class="caret"></span>
                </button>

                <div class="dropdown-content animated">
                    <div class="help">
                        <div>Enter a value without a dollar sign. No need to add a negative sign for an expense transaction.</div>
                        <div>For example:</div>
                        <div>
                            <div>5</div>
                            <div>5.5</div>
                            <div>5.55</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input
            ng-model="new_transaction.total"
            ng-keyup="insertTransaction($event.keyCode)"
            class="total mousetrap form-control"
            placeholder="$"
            type='text'>
    </div>

    <div>
        <label>Choose a transaction type</label>

        <select
            ng-model="new_transaction.type"
            name=""
            ng-keyup="insertTransaction($event.keyCode)"
            id="select_transaction_type"
            class="mousetrap form-control">
            <option value="income">Credit</option>
            <option value="expense">Debit</option>
            <option value="transfer">Transfer</option>
        </select>
    </div>

    <div>
        <?php include($templates . '/home/new-transaction/accounts.php'); ?>
    </div>
	
    <div>

        <div class="help-row">
            <label>Check this box if your transaction is reconciled</label>

            <div dropdown class="dropdown-directive">
                <button ng-click="showDropdown()" class="btn btn-info btn-xs">
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

        <checkbox
            model="new_transaction.reconciled">
        </checkbox>

    </div>

    <div>
        <?php include($templates . '/home/new-transaction/tags.php'); ?>
    </div>

    <div>
        <div class="help-row">
            <button ng-click="insertTransaction(13)" id="add-transaction" class="btn btn-success">Add transaction</button>

            <div dropdown class="dropdown-directive">
                <button ng-click="showDropdown()" class="btn btn-info btn-xs">
                    Help
                    <span class="caret"></span>
                </button>

                <div class="dropdown-content animated">
                    <div class="help">
                        <div>Make sure you don't have the tags dropdown open when you try to click this button, or it won't work.</div>
                        <div>First close the dropdown by clicking outside of it, and then click the button to insert the transaction.</div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
