<script id="accounts-page-template" type="x-template">

<div>
    <edit-account
            :accounts.sync="accounts"
    >
    </edit-account>

    <div id="accounts">

        <div class="create-new-account">
            <label>Create a new account</label>

            <new-account
                    :accounts.sync="accountsRepository.accounts"
            >
            </new-account>

        </div>

        <table class="">
            <tr>
                <th>Name</th>
                <th class="balance">Balance</th>
            </tr>
            <tr v-for="account in accountsRepository.accounts | orderBy 'name'">
                <td
                        v-on:click="showEditAccountPopup(account)"
                        class="pointer">
                    @{{ account.name }}
                </td>
                <td class="balance">@{{ account.balance | numberFilter 2 }}</td>
                <td>
                    <button v-on:click="viewTransactionsForAccount(account)" class="btn btn-default btn-sm">View Transactions</button>
                </td>
            </tr>
        </table>
    </div>
</div>

</script>