<script id="favourite-transactions-page-template" type="x-template">

    <div id="favourite-transactions-page">


        <new-favourite-transaction
                :budgets="budgets"
                :favourite-transactions.sync="favouriteTransactions"
                :accounts="accounts"
        >
        </new-favourite-transaction>

        <update-favourite-transaction
                :budgets="budgets"
                :favourite-transactions.sync="favouriteTransactions"
                :accounts="accounts"
        >
        </update-favourite-transaction>

        <div>
            <h2>Favourite transactions</h2>

            <table id="favourite-transactions" >
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Merchant</th>
                    <th>Total</th>
                    <th>Account</th>
                    <th>Tags</th>
                </tr>

                <tr
                    v-for="favourite in favouriteTransactions"
                    v-on:click="showUpdateFavouriteTransactionPopup(favourite)"
                    class="pointer"
                >
                    <td>@{{ favourite.name }}</td>
                    <td>@{{ favourite.type }}</td>
                    <td>@{{ favourite.description }}</td>
                    <td>@{{ favourite.merchant }}</td>
                    <td>@{{ favourite.total }}</td>
                    <td>
                        <span v-if="favourite.account">@{{ favourite.account.name }}</span>
                        <span v-if="favourite.fromAccount">from @{{ favourite.fromAccount.name }}</span>
                        <span v-if="favourite.fromAccount && favourite.toAccount"> </span>
                        <span v-if="favourite.toAccount">to @{{ favourite.toAccount.name }}</span>
                    </td>

                    <td class="budgets">
                        <li
                            v-for="budget in favourite.budgets"
                            v-bind:class="{
                                'tag-with-fixed-budget': budget.type === 'fixed',
                                'tag-with-flex-budget': budget.type === 'flex',
                                'tag-without-budget': budget.type === 'unassigned'
                            }"
                            class="label label-default"
                        >
                            <span>@{{ budget.name }}</span>
                            <span class="type">@{{ budget.type }}</span>
                        </li>
                    </td>

                </tr>
            </table>

        </div>




    </div>

</script>