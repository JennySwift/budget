<div class="flex-grow-2">
    <table id="transactions" class="table">
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
                <th>
                    <i class="fa fa-pencil-square-o"></i>
                </th>
                <th>budgets</th>
                <!-- <th>mass delete</th> -->
            </tr>
        </thead>
        <tbody ng-repeat="transaction in transactions" ng-style="{color: colors[transaction.type]}" id="transaction.id" class="add_to_search_total results-transaction-tbody {{transaction.type}}">
            
            <tr class="results_inner_div">
                <td ng-show="show.date">{{transaction.date.user}}</td>
                <td ng-show="show.description" class="max-width-md">
                    <div>{{transaction.description}}</div>    
                </td>
                <td ng-show="show.merchant" class="max-width-md">
                    <div>{{transaction.merchant}}</div>
                </td>
                <td ng-show="show.total">{{transaction.total}}</td>
                <td ng-show="show.account" class="max-width-md">{{transaction.account.name}}</td>
                <td ng-show="show.reconciled">
                    <input ng-model="transaction.reconciled" ng-change="updateReconciliation(transaction.id, transaction.reconciled)" type="checkbox">
                </td>
                <td ng-show="show.dlt" ng-click="deleteTransaction(transaction.id)" class="pointer">
                    <i class="fa fa-times"></i>
                </td>
                <td>
                    <button ng-click="updateTransactionSetup(transaction)" class="fa fa-pencil-square-o"></button>
                </td>
                <td>
                    <button ng-if="transaction.multiple_budgets" ng-class="{'allocated': transaction.allocated, 'not-allocated': !transaction.allocated}" ng-click="showAllocationPopup(transaction)">allocate</button>
                </td>
                <!-- <td class="mass-delete-checkbox-container"></td> -->
            </tr>
    
            <tr ng-show="show.tags" class="results-tag-location-container tag-location-container">
                <td colspan="8">
                    <li ng-click="removeResultTag(this);" ng-repeat="tag in transaction.tags" ng-class="{'tag-with-fixed-budget': tag.fixed_budget !== null, 'tag-with-flex-budget': tag.flex_budget !== null, 'tag-without-budget': tag.fixed_budget === null || tag.flex_budget === null}" class="label label-default" data-id="{{tag.id}}" data-allocated-percent="{{tag.allocated_percent}}" data-allocated-fixed="{{tag.allocated_fixed}}" data-allocated_fixed="{{tag.allocated_fixed}}">{{tag.name}}</li>
                </td>
                <td colspan="1" class="budget-tag-info">{{transaction.allocate}}</td>
            </tr>
    
            <tr class="separator"></tr>
    
        </tbody>
     </table>
</div>


<!-- if ($allocated_percent == null) {
    $allocated_fixed = $amount;
}

//perhaps this should be outside the each function? It seems to work for the moment, but if I had to change it when using splice, above.
if ($budget_counter > 1) {
    $allocate = '<button class="allocate">allocate</button>';
}
if ($allocation == null) {
    $allocation = "not-allocated";
} -->