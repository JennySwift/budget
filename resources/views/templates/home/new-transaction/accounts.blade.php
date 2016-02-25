<div v-cloak v-show="new_transaction.type !== 'transfer'">
    <label>Select an account</label>
    <select
        v-model="new_transaction.account_id"
        v-on:keyup="insertTransaction($event.keyCode)"
        class="form-control">
        <option v-repeat="account in accounts" value="[[account.id]]">[[account.name]]</option>
    </select>
</div>

<div v-cloak v-show="new_transaction.type === 'transfer'">
    <label for="" class="center">Select the account you are transferring money from</label>

    <select
        v-model="new_transaction.from_account_id"
        v-on:keyup="insertTransaction($event.keyCode)"
        type="text"
        id="transfer-from-account-select"
        class="account-dropdown form-control">
        <option v-repeat="account in accounts" value="[[account.id]]">[[account.name]]</option>
    </select>

</div>

<div v-cloak v-show="new_transaction.type === 'transfer'">
    <label for="" class="center">Select the account you are transferring money to</label>

    <select
        v-model="new_transaction.to_account_id"
        v-on:keyup="insertTransaction($event.keyCode)"
        type="text"
        id="transfer-from-account-select"
        class="account-dropdown form-control">
        <option v-repeat="account in accounts" value="[[account.id]]">[[account.name]]</option>
    </select>
</div>