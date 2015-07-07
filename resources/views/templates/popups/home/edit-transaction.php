<!-- ==============================edit transaction============================== -->

<div ng-show="show.edit_transaction" ng-cloak class="popup-outer">
	<div id="edit-transaction" class="popup-inner"> <!-- this div is so the is has same margin as other -->
		<input ng-model="edit_transaction.date.user" id="edit-transaction-date" placeholder="date" type='text'>
		<input ng-model="edit_transaction.description" placeholder="description" type='text'>
		<input ng-model="edit_transaction.merchant" placeholder="merchant" type='text'>
		<input ng-model="edit_transaction.total" placeholder="$" type='text'>
		
		<select ng-model="edit_transaction.type" name="">
		    <option value="income">Credit</option>
		    <option value="expense">Debit</option>
		    <option value="transfer">Transfer</option>
		</select>
		
		<select ng-model="edit_transaction.account.id" ng-change="fixEditTransactionAccount()" name="" id="edit-transaction-account">
			<option ng-repeat="account in accounts" value="[[account.id]]">{{account.name]]</option>
		</select>
		
		<div ng-show="edit_transaction.type === 'transfer'" class="col-xs-12">
			<select ng-model="edit_transaction.from_account" type="text" id="transfer-from-account-select"></select>
		</div>
		
		<div ng-show="edit_transaction.type === 'transfer'" class="col-xs-12">
			<select ng-model="edit_transaction.to_account" type="text" id="transfer-to-account-select"></select>
		</div>
		
		<div class="cb-container">
			<span class="label-text">Reconciled</span>
		    <div class="cb-slider-wrapper">
		        <input ng-model="edit_transaction.reconciled" type="checkbox" id="edit-transaction-reconciled">
		        <label for="edit-transaction-reconciled">
		            <span class="label-icon"></span>
		        </label>
		    </div>
		</div>
		
		<!-- ================tag wrapper================ -->

		<div class="tag-wrapper">
			<div class="tag-input-wrapper">
				
				<input ng-model="typing.edit_transaction.tag" ng-focus="edit_transaction.dropdown = true" ng-blur="edit_transaction.dropdown = false" ng-keyup="filterTags($event.keyCode, typing.edit_transaction.tag, edit_transaction.tags, 'edit_transaction')" placeholder="tags" type='text'>
				
				<div ng-if="edit_transaction.dropdown" class="tag-dropdown">
					<li ng-repeat="tag in autocomplete.tags" ng-class="{'selected': tag.selected}" data-id="{{tag.id]]">{{tag.name]]</li>
				</div>
			
			</div>
			

			<div ng-show="edit_transaction.tags.length > 0" class="tag-display">
				<li ng-repeat="tag in edit_transaction.tags" ng-click="removeTag(tag, edit_transaction.tags, 'edit_transaction')" ng-class="{'tag-with-budget': tag.has_budget === 'yes', 'tag-without-budget': tag.has_budget === 'no'}" class="label label-default removable-tag" data-id="{{tag.id]]" data-allocated-percent="{{tag.allocated_percent]]" data-allocated-fixed="{{tag.allocated_fixed]]" data-amount="{{tag.amount]]">
					{{tag.name]]
					<i class="fa fa-times"></i>
				</li>
			</div>
		</div>

		<!-- ================end tag wrapper================ -->
	
		<div class="btn-wrapper">
			<button ng-click="multiSearch(); show.edit_transaction = false" class="cancel">Cancel</button>
			<button ng-click="updateTransaction()" class="save">Save</button>
		</div>
	
	</div>
</div>
