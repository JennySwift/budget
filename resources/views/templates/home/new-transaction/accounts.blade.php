<div v-cloak v-show=" newTransaction.type !== 'transfer'">

    <div class="form-group">
        <label for="new-transaction-account">Account</label>

        <select
            v-model="newTransactionAccount"
            v-on:keyup.13="insertTransaction()"
            id="new-transaction-account"
            class="form-control"
        >
            <option
                v-for="account in accounts"
                v-bind:value="account"
            >
                @{{ account.name }}
            </option>
        </select>
    </div>

</div>

<div v-cloak v-show=" newTransaction.type === 'transfer'">

    <div class="form-group">
        <label for="new-transaction-from-account">Select the account your are transferring money from</label>

        <select
            v-model="newTransaction.fromAccount"
            v-on:keyup.13="insertTransaction()"
            id="new-transaction-from-account"
            class="form-control"
        >
            <option
                v-for="account in accounts"
                v-bind:value="account"
            >
                @{{ account.name }}
            </option>
        </select>
    </div>

</div>

<div v-cloak v-show=" newTransaction.type === 'transfer'">

    <div class="form-group">
        <label for="new-transaction-to-account">Select the account you are transferring money @stop</label>

        <select
            v-model="newTransaction.toAccount"
            v-on:keyup.13="insertTransaction()"
            id="new-transaction-to-account"
            class="form-control"
        >
            <option
                v-for="account in accounts"
                v-bind:value="account"
            >
                @{{ account.name }}
            </option>
        </select>
    </div>

</div>