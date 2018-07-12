<template>
    <div id="favourite-transactions-page">


        <new-favourite-transaction></new-favourite-transaction>

        <favourite-transaction></favourite-transaction>

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
                    v-for="favourite in shared.favouriteTransactions"
                    v-on:click="showFavouriteTransactionPopup(favourite)"
                    class="pointer"
                >
                    <td>{{ favourite.name }}</td>
                    <td>{{ favourite.type }}</td>
                    <td>{{ favourite.description }}</td>
                    <td>{{ favourite.merchant }}</td>
                    <td>{{ favourite.total }}</td>
                    <td>
                        <span v-if="favourite.account">{{ favourite.account.name }}</span>
                        <span v-if="favourite.fromAccount">from {{ favourite.fromAccount.name }}</span>
                        <span v-if="favourite.fromAccount && favourite.toAccount"> </span>
                        <span v-if="favourite.toAccount">to {{ favourite.toAccount.name }}</span>
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
                            <span>{{ budget.name }}</span>
                            <span class="type">{{ budget.type }}</span>
                        </li>
                    </td>

                </tr>
            </table>

        </div>




    </div>

</template>

<script>
    import NewFavouriteTransactionComponent from './NewFavouriteTransactionComponent.vue'
    import FavouriteTransactionComponent from './FavouriteTransactionComponent.vue'
    import helpers from '../../repositories/helpers/Helpers'
    export default {
        data: function () {
            return {
                shared: store.state,
                newFavourite: {
                    budgets: []
                },
            };
        },
        components: {
            'new-favourite-transaction': NewFavouriteTransactionComponent,
            'favourite-transaction': FavouriteTransactionComponent
        },
        computed: {

        },
        methods: {

            /**
             *
             * @param favouriteTransaction
             */
            showFavouriteTransactionPopup: function (favouriteTransaction) {
                store.set(favouriteTransaction, 'selectedFavouriteTransaction');
                helpers.showPopup('favourite-transaction');
            },

        },
        props: [
            //data to be received from parent
        ],
        mounted: function () {

        }
    }
</script>

<style lang="scss" type="text/scss">
    #favourite-transactions-page {
        margin: 50px 50px;
        > * {
            margin: 50px 50px;
        }

        #new-favourite {
            width: 500px;
            margin: auto;
            h2 {
                margin-bottom: 31px;
            }
            //> div {
            //    display: flex;
            //    justify-content: space-between;
            //}
            //select {
            //    max-width: 300px;
            //}
        }
        #favourite-transactions {
            //        width: 500px;
            margin: auto;
            .budgets {
                text-align: left;
            }
        }
    }
</style>