<script id="favourite-transactions-page-template" type="x-template">

    <div id="favourite-transactions-page">


        <new-favourite-transaction></new-favourite-transaction>

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
                    <th></th>
                </tr>

                <tr v-for="favourite in favouriteTransactions">
                    <td>@{{ favourite.name }}</td>
                    <td>@{{ favourite.type }}</td>
                    <td>@{{ favourite.description }}</td>
                    <td>@{{ favourite.merchant }}</td>
                    <td>@{{ favourite.total }}</td>
                    <td><span v-if="favourite.account">@{{ favourite.account.name }}</span></td>
                    <td>
                        <span v-for="budget in favourite.budgets" class="badge">@{{ budget.name }}</span>
                    </td>
                    <td><button v-on:click="deleteFavouriteTransaction(favourite)" class="btn-xs btn-danger">Delete</button></td>

                </tr>
            </table>

        </div>




    </div>

</script>