
<div ng-controller="TransactionsController" ng-show="show.edit_transaction" ng-cloak class="popup-outer">
    <div id="edit-transaction" class="popup-inner">
        <input ng-model="edit_transaction.date.user" id="edit-transaction-date" placeholder="date" type='text'>
		<input ng-model="edit_transaction.description" placeholder="description" type='text'>
		<input ng-model="edit_transaction.merchant" placeholder="merchant" type='text'>
		<input ng-model="edit_transaction.total" placeholder="$" type='text'>
		
		<select ng-model="edit_transaction.type" class="form-control">
		    <option value="income">Credit</option>
		    <option value="expense">Debit</option>
		    <option value="transfer">Transfer</option>
		</select>
		
		<select ng-model="edit_transaction.account.id"
                ng-change="fixEditTransactionAccount()"
                id="edit-transaction-account"
                class="form-control">
			        <option ng-repeat="account in accounts" value="[[account.id]]">[[account.name]]</option>
		</select>
		
		<div ng-show="edit_transaction.type === 'transfer'" class="col-xs-12">
			<select
                ng-model="edit_transaction.from_account"
                type="text"
                id="transfer-from-account-select"
                class="form-control">
                <option ng-repeat="account in accounts" value="[[account.id]]">[[account.name]]</option>
            </select>
		</div>
		
		<div ng-show="edit_transaction.type === 'transfer'" class="col-xs-12">
			<select
                ng-model="edit_transaction.to_account"
                type="text"
                id="transfer-to-account-select"
                class="form-control">
                <option ng-repeat="account in accounts" value="[[account.id]]">[[account.name]]</option>
            </select>
		</div>

        <div>
            <label>Reconciled</label>

            <checkbox
                model="edit_transaction.reconciled">
            </checkbox>
        </div>

        <tag-autocomplete-directive
            chosenTags="edit_transaction.tags"
            dropdown="edit_transaction.dropdown"
            tags="tags"
            multipleTags="true">
        </tag-autocomplete-directive>

		<div class="btn-wrapper">
			<button ng-click="filterTransactions(); show.edit_transaction = false" class="cancel">Cancel</button>
			<button ng-click="updateTransaction()" class="save">Save</button>
		</div>
	
	</div>
</div>