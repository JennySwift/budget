
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

        <label>Enter a description (optional)</label>

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

        <label>Enter a merchant (optional)</label>

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
        <label>Enter the total</label>

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
        <label>Check this box if your transaction is reconciled</label>

        <div class="cb-container">

            <span class="label-text">Reconciled</span>

            <div class="cb-slider-wrapper">

                <input
                    ng-model="new_transaction.reconciled"
                    type="checkbox"
                    id="new-transaction-reconciled">

                <label for="new-transaction-reconciled">
                    <span class="label-icon"></span>
                </label>

            </div>

        </div>
    </div>

    <div>
        <?php include($templates . '/home/new-transaction/tags.php'); ?>
    </div>

</div>
