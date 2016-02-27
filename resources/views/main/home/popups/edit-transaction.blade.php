
<div v-controller="TransactionsController" v-show="show.edit_transaction" v-cloak class="popup-outer">

    <div id="edit-transaction" class="popup-inner">

        <input v-model="edit_transaction.userDate" id="edit-transaction-date" placeholder="date" type='text'>
		<input v-model="edit_transaction.description" placeholder="description" type='text'>
		<input v-model="edit_transaction.merchant" placeholder="merchant" type='text'>
		<input v-model="edit_transaction.total" placeholder="$" type='text'>
		
		<select v-model="edit_transaction.type" class="form-control">
		    <option value="income">Credit</option>
		    <option value="expense">Debit</option>
		    <option value="transfer">Transfer</option>
		</select>
		
		<select v-model="edit_transaction.account_id"
                id="edit-transaction-account"
                class="form-control">
			        <option v-for="account in accounts" value="[[account.id]]">[[account.name]]</option>
		</select>
		
		<div v-show="edit_transaction.type === 'transfer'" class="col-xs-12">
			<select
                v-model="edit_transaction.from_account"
                type="text"
                id="transfer-from-account-select"
                class="form-control">
                <option v-for="account in accounts" value="[[account.id]]">[[account.name]]</option>
            </select>
		</div>
		
		<div v-show="edit_transaction.type === 'transfer'" class="col-xs-12">
			<select
                v-model="edit_transaction.to_account"
                type="text"
                id="transfer-to-account-select"
                class="form-control">
                <option v-for="account in accounts" value="[[account.id]]">[[account.name]]</option>
            </select>
		</div>

        <label>Duration</label>
        <div>[[edit_transaction.minutes]]</div>
        <input v-model="edit_transaction.duration"
               placeholder="duration"
               type='text'>

        <div>
            <label>Reconciled</label>

            <checkbox
                model="edit_transaction.reconciled">
            </checkbox>
        </div>

        <tag-autocomplete-directive
            v-if="edit_transaction.type !== 'transfer'"
            chosenTags="edit_transaction.budgets"
            dropdown="edit_transaction.dropdown"
            tags="budgets"
            multipleTags="true">
        </tag-autocomplete-directive>

		<div class="btn-wrapper">
			<button v-on:click="runFilter(); show.edit_transaction = false" class="cancel">Cancel</button>
			<button v-on:click="updateTransaction()" class="save">Save</button>
		</div>
	
	</div>
</div>
