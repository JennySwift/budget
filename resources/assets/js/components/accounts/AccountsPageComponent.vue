<template>
    <div>
        <account-popup></account-popup>

        <div id="accounts">

            <div class="create-new-account">
                <label>Create a new account</label>

                <new-account></new-account>

            </div>

            <table class="">
                <tr>
                    <th>Name</th>
                    <th class="balance">Balance</th>
                </tr>
                <tr v-for="account in shared.accountsWithBalances">
                    <td
                        v-on:click="showAccountPopup(account)"
                        class="pointer">
                        {{ account.name }}
                    </td>
                    <td class="balance">{{ account.balance | numberFilter(2) }}</td>
                    <td>
                        <button v-on:click="viewTransactionsForAccount(account)" class="btn btn-default btn-sm">View Transactions</button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</template>

<script>
    import helpers from '../../repositories/helpers/Helpers'
    import FilterRepository from '../../repositories/FilterRepository'
    import NewAccountComponent from './NewAccountComponent.vue'
    import AccountPopupComponent from './AccountPopupComponent.vue'
    export default {
        data: function () {
            return {
                shared: store.state,
            };
        },
        computed: {
            accounts: function () {
              return _.orderBy(this.shared.accounts, 'name');
            }
        },
        components: {
            'account-popup': AccountPopupComponent,
            'new-account': NewAccountComponent
        },
        filters: {
            /**
             *
             * @param number
             * @param howManyDecimals
             * @returns {Number}
             */
            numberFilter: function (number, howManyDecimals) {
                return helpers.numberFilter(number, howManyDecimals);
            },
        },
        methods: {

            /**
             *
             * @param account
             */
            showAccountPopup: function (account) {
                store.set(account, 'selectedAccount');
                helpers.showPopup('account');
            },

            /**
             *
             * @param account
             */
            viewTransactionsForAccount: function (account) {
                var currentNumToFetch = store.state.filter.numToFetch;
                FilterRepository.resetFilter();
                store.add(account.id, 'filter.accounts.in');
                //Show the same number of transactions as the filter was previously set to
                store.set(currentNumToFetch, 'filter.numToFetch');
                store.set(currentNumToFetch, 'filter.displayTo');
                helpers.goToRoute('/');
            }
        },
        props: [
            //data to be received from parent
        ],
        mounted: function () {
            //Putting this here rather than loading just on page load in case the account balances need updating when this page is visited
            store.getAccounts(this);
        }
    }
</script>

<style lang="scss" type="text/scss">
    @import '../../../sass/mixins';
    #accounts {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 50px;
        > * {
            max-width: 500px;
        }
        .create-new-account {
            box-shadow: 3px 3px 5px #777;
            margin-bottom: 30px;
            border-radius: 4px;
            padding: 14px;
            width: 293px;
            .flex {
                display: flex;
                justify-content: space-between;
                align-items: flex-end;
                .form-group {
                    margin-bottom: 0;
                }
            }
        }
        tr {
            .btn-danger {
                visibility: hidden;
            }
            &:hover {
                .btn-danger {
                    visibility: visible;
                }
            }
            td, th {
                text-align: left;
            }
            td {
                font-size: 17px;
                height: 32px;
            }
            .balance {
                text-align: right;
            }
        }
    }
</style>