
<div ng-show="show.new_transaction" ng-style="{color: colors[new_transaction.type]}" id="new-transaction" class="bg-grey"> <!-- this div is so the is has same margin as other -->
	<input ng-value="new_transaction.date.entered" ng-keyup="insertTransaction($event.keyCode)" id="date" placeholder="date" type='text' class="date mousetrap form-control">
	<div class="autocomplete-container">
		<input ng-model="new_transaction.description" ng-blur="show.autocomplete.transactions = false" ng-keyup="filterTransactions($event.keyCode, new_transaction.description, 'description')" id="new-transaction-description" class="description mousetrap form-control" placeholder="description" type='text'>
		<input ng-model="new_transaction.merchant" ng-blur="show.autocomplete.transactions = false" ng-keyup="filterTransactions($event.keyCode, new_transaction.merchant, 'merchant')" id="new-transaction-merchant" class="merchant mousetrap form-control" placeholder="merchant" type='text'>
		<?php include($inc . '/new-transaction-autocomplete.php'); ?>
	</div>

	<input ng-model="new_transaction.total" ng-keyup="insertTransaction($event.keyCode)" class="total mousetrap form-control" placeholder="$" type='text'>
	
	<select ng-model="new_transaction.type" name="" ng-keyup="insertTransaction($event.keyCode)" id="select_transaction_type" class="mousetrap form-control">
	    <option value="income">Credit</option>
	    <option value="expense">Debit</option>
	    <option value="transfer">Transfer</option>
	</select>
	
	<div ng-show="new_transaction.type !== 'transfer'">
		<!-- <label for="" class="center">account</label> -->
		<select ng-model="new_transaction.account" ng-keyup="insertTransaction($event.keyCode)">
			<option ng-repeat="account in accounts" value="{{account.id}}">{{account.name}}</option>
		</select>
	</div>

	<div ng-show="new_transaction.type === 'transfer'">
		<label for="" class="center">from account</label>
		<select ng-model="new_transaction.from_account" ng-keyup="insertTransaction($event.keyCode)" type="text" id="transfer-from-account-select" class="account-dropdown">
			<!-- <option selected>from account</option> -->
			<option ng-repeat="account in accounts" value="{{account.id}}">{{account.name}}</option>
		</select>

		<label for="" class="center">to account</label>
		<select ng-model="new_transaction.to_account" ng-keyup="insertTransaction($event.keyCode)" type="text" id="transfer-from-account-select" class="account-dropdown">
			<!-- <option value="">to account</option> -->
			<option ng-repeat="account in accounts" value="{{account.id}}">{{account.name}}</option>
		</select>
	</div>

	<div class="cb-container">
	    <span class="label-text">Reconciled</span>
	    <div class="cb-slider-wrapper">
	        <input ng-model="new_transaction.reconciled" type="checkbox" id="new-transaction-reconciled">
	        <label for="new-transaction-reconciled">
	            <span class="label-icon"></span>
	        </label>
	    </div>
	</div>

	<!-- ================tag wrapper================ -->

	<div class="tag-wrapper">
		<div class="tag-input-wrapper">
			
			<input ng-model="typing.new_transaction.tag" ng-focus="new_transaction.dropdown = true" ng-blur="new_transaction.dropdown = false" ng-keyup="filterTags($event.keyCode, typing.new_transaction.tag, new_transaction.tags, 'new_transaction')" placeholder="tags" type='text'>
			
			<div ng-if="new_transaction.dropdown" class="tag-dropdown">
				<li ng-repeat="tag in autocomplete.tags" ng-class="{'selected': tag.selected}" data-id="{{tag.id}}">{{tag.name}}</li>
			</div>
		
		</div>
		

		<div ng-show="new_transaction.tags.length > 0" class="tag-display">
			<li ng-repeat="tag in new_transaction.tags" ng-click="removeTag(tag, new_transaction.tags, 'new_transaction')" ng-class="{'tag-with-budget': tag.fixed_budget !== null || tag.flex_budget !== null, 'tag-without-budget': tag.fixed_budget === null || tag.flex_budget === null}" class="label label-default removable-tag" data-id="{{tag.id}}" data-allocated-percent="{{tag.allocated_percent}}" data-allocated-fixed="{{tag.allocated_fixed}}" data-amount="{{tag.amount}}">
				{{tag.name}}
				<i class="fa fa-times"></i>
			</li>
		</div>
	</div>

	<!-- <button ng-if="new_transaction.multiple_budgets" ng-click="show.new_transaction_allocation_popup = true">allocate</button> -->
	
</div>
