<template>
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
</template>

<script>
    export default {
        data: function () {
            return {
                accountsRepository: AccountsRepository.state,
            };
        },
        components: {},
        filters: {
            /**
             *
             * @param number
             * @param howManyDecimals
             * @returns {Number}
             */
            numberFilter: function (number, howManyDecimals) {
                return HelpersRepository.numberFilter(number, howManyDecimals);
            },
        },
        methods: {

            /**
             *
             * @param account
             */
            showEditAccountPopup: function (account) {
                $.event.trigger('show-edit-account-popup', [account]);
            },

            /**
             *
             * @param account
             */
            viewTransactionsForAccount: function (account) {
                var currentNumToFetch = FilterRepository.state.filter.numToFetch;
                var filter = FilterRepository.resetFilter();
                filter.accounts.in.push(account.id);
                //Show the same number of transactions as the filter was previously set to
                filter.numToFetch = currentNumToFetch;
                filter.displayTo = currentNumToFetch;
                FilterRepository.setFilter(filter);
                router.go('/');
            }
        },
        props: [
            //data to be received from parent
        ],
        mounted: function () {
            //Putting this here rather than loading just on page load in case the account balances need updating when this page is visited
            AccountsRepository.getAccounts(this);
        }
    }
</script>