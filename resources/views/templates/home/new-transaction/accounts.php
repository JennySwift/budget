<div ng-cloak ng-show="new_transaction.type !== 'transfer'">
    <label>Select an account</label>
    <select ng-model="new_transaction.account" ng-keyup="insertTransaction($event.keyCode)">
        <option ng-repeat="account in accounts" value="[[account.id]]">[[account.name]]</option>
    </select>
</div>

<div ng-cloak ng-show="new_transaction.type === 'transfer'">

    <label for="" class="center">Select the account you are transferring money from</label>

    <select
        ng-model="new_transaction.from_account"
        ng-keyup="insertTransaction($event.keyCode)"
        type="text"
        id="transfer-from-account-select"
        class="account-dropdown">
        <option ng-repeat="account in accounts" value="[[account.id]]">[[account.name]]</option>
    </select>

    <label for="" class="center">Select the account you are transferring money to</label>

    <select
        ng-model="new_transaction.to_account"
        ng-keyup="insertTransaction($event.keyCode)"
        type="text"
        id="transfer-from-account-select"
        class="account-dropdown">
        <option ng-repeat="account in accounts" value="[[account.id]]">[[account.name]]</option>
    </select>

</div>