<!-- <header class="bg-yellow">header</header>
<div class="row fixed-bottom width-100">
	<div class="vertical-center bg-blue">
		<p class="bg-red vertical-center">some text</p>
	</div>
	
	<div id="css-practice" class="bg-green">
		<p class="bg-red">some text lorem ipsum</p>
	</div>
</div>
<footer class="bg-yellow">footer</footer> -->

<div class="row">
	<ul class="bg-blue">
		<li>one</li>
		<li>two</li>
		<li>three</li>
		<li>four</li>
		<li>five</li>
		<li>six</li>
		<li>seven</li>
		<li>eight</li>
	</ul>
	
	<table class="bg-yellow">
		<thead>
		    <tr class="bg-dark">
		        <th ng-show="show.date">Date</th>
		        <th ng-show="show.description">Description</th>
		        <th ng-show="show.merchant">Merchant</th>
		        <th ng-show="show.total">
		            <span class="total fa fa-dollar"></span>
		        </th>
		        <th ng-show="show.account">Account</th>
		        <th ng-show="show.reconciled" class="reconcile">R</th>
		        <th ng-show="show.dlt">
		            <i class="fa fa-times"></i>
		        </th>
		        <!-- <th>mass delete</th> -->
		    </tr>
		</thead>
		
		<tbody ng-repeat="transaction in transactions_limited" id="transaction.id" class="add_to_search_total results-transaction-tbody {{transaction.type}} {{transaction.allocation}}">
		    
		    <tr class="results_inner_div">
		        <td ng-show="show.date">{{transaction.date}}</td>
		        <td ng-show="show.description" class="max-width-md">
		            <div>{{transaction.description}}</div>    
		        </td>
		        <td ng-show="show.merchant" class="max-width-md">
		            <div>{{transaction.merchant}}</div>
		        </td>
		        <td ng-show="show.total">{{transaction.total}}</td>
		        <td ng-show="show.account">{{transaction.account.name}}</td>
		        <td ng-show="show.reconciled">
		            <input ng-model="transaction.reconciled" type="checkbox">
		        </td>
		        <td ng-show="show.dlt" ng-click="deleteTransaction(transaction.id)">
		            <i class="fa fa-times"></i>
		        </td>
		        <!-- <td class="mass-delete-checkbox-container"></td> -->
		    </tr>

		    <tr ng-show="show.tags" class="results-tag-location-container tag-location-container">
		        <td colspan="7">
		            <li ng-click="removeResultTag(this);" ng-repeat="tag in transaction.tags" ng-class="{'tag-with-budget': tag.budget !== null || tag.percent !== null, 'tag-without-budget': tag.budget === null || tag.percent === null}" class="label label-default" data-id="{{tag.id}}" data-allocated-percent="{{tag.allocated_percent}}" data-allocated-fixed="{{tag.allocated_fixed}}" data-amount="{{tag.amount}}">{{tag.name}}</li>
		        </td>
		        <td colspan="1" class="budget-tag-info">{{transaction.allocate}}</td>
		    </tr>

		    <tr class="separator"></tr>

		</tbody>
	</table>

	<ul class="bg-blue">
		<li>one</li>
		<li>two</li>
		<li>three</li>
		<li>four</li>
		<li>five</li>
		<li>six</li>
		<li>seven</li>
		<li>eight</li>
	</ul>
</div>