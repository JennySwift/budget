<template>
    <tbody>


    <tr class="results_inner_div">

        <td
            v-show="shared.transactionPropertiesToShow.date"
            v-on:click="showTransactionPopup(transaction)"
            class="pointer"
        >
            {{ transaction.date | formatDateForUser}}
        </td>

        <td
            v-show="shared.transactionPropertiesToShow.description"
            v-on:click="showTransactionPopup(transaction)"
            class="description pointer"
        >
            <div>
                <div>{{ transaction.description }}</div>
            </div>

            <div v-if="transaction.description" class="tooltip-container">
                <div class="tooltip">{{ transaction.description }}</div>
            </div>
        </td>

        <td
            v-show="shared.transactionPropertiesToShow.merchant"
            v-on:click="showTransactionPopup(transaction)"
            class="merchant pointer"
        >
            <div>
                <div>{{ transaction.merchant }}</div>
            </div>

            <div v-if="transaction.merchant" class="tooltip-container">
                <div class="tooltip">{{ transaction.merchant }}</div>
            </div>
        </td>

        <td
            v-show="shared.transactionPropertiesToShow.total"
            v-on:click="showTransactionPopup(transaction)"
            class="pointer"
        >
            <!--<span class="badge badge-{{ transaction.type }}">{{ transaction.total }}</span>-->
            {{ transaction.total | numberFilter(2) }}
        </td>

        <td
            v-show="shared.transactionPropertiesToShow.account"
            v-on:click="showTransactionPopup(transaction)"
            class="max-width-md pointer"
        >
            {{ transaction.account.name }}
        </td>

        <td
            v-show="shared.transactionPropertiesToShow.duration"
            v-on:click="showTransactionPopup(transaction)"
            class="pointer"
        >
            <span v-if="transaction.minutes">{{ transaction.minutes | formatDurationFilter }}</span>
        </td>

        <td v-show="shared.transactionPropertiesToShow.reconciled"
        >
            <input v-model="transaction.reconciled" v-on:change="updateTransaction()" type="checkbox">
        </td>

        <td v-show="shared.transactionPropertiesToShow.allocated">
            <button
                v-if="transaction.multipleBudgets"
                v-bind:class="{
                            'allocated': transaction.validAllocation,
                            'btn-success': transaction.validAllocation,
                            'not-allocated': !transaction.validAllocation,
                            'btn-danger': !transaction.validAllocation,
                        }"
                class="btn btn-sm"
                v-on:click="showAllocationPopup(transaction)">
                allocate
            </button>
        </td>

    <!--</tr>-->

    <!--Budgets-->
    <tr
        v-if="transaction.budgets"
        v-show="shared.transactionPropertiesToShow.budgets && shared.transaction.budgets.length > 0"
        class="tag-location-container">

        <td colspan="9">
            <li
                v-for="budget in transaction.budgets"
                v-bind:class="{
                'tag-with-fixed-budget': budget.type === 'fixed',
                'tag-with-flex-budget': budget.type === 'flex',
                'tag-without-budget': budget.type === 'unassigned'
            }"
                class="label label-default budget">
                <!--data-id="{{ tag.id }}"-->
                <!--data-allocated-percent="{{ budget.allocated_percent }}"-->
                <!--data-allocated-fixed="{{ budget.allocated_fixed }}"-->
                <!--data-allocated_fixed="{{ budget.allocated_fixed }}"-->

                <span>{{ budget.name }}</span>
                <span v-if="budget.pivot">{{ budget.pivot.calculated_allocation }}</span>
                <span class="type">{{ budget.type }}</span>
            </li>
        </td>

    </tr>

    </tbody>

</template>

<script>
    import helpers from '../../repositories/helpers/Helpers'
    import TotalsRepository from '../../repositories/TotalsRepository'
    import TransactionsRepository from '../../repositories/TransactionsRepository'
    export default {
        data: function () {
            return {
                shared: store.state
            };
        },
        components: {},
        filters: {
            /**
             *
             * @param minutes
             * @returns {*}
             */
            formatDurationFilter: function (minutes) {
                return helpers.formatDurationToHoursAndMinutes(minutes);
            },

            /**
             *
             * @param number
             * @param howManyDecimals
             * @returns {Number}
             */
            numberFilter: function (number, howManyDecimals) {
                return helpers.numberFilter(number, howManyDecimals);
            },

            /**
             *
             * @param date
             * @returns {*|String}
             */
            formatDateForUser: function (date) {
                return helpers.formatDateForUser(date);
            }
        },
        methods: {

            /**
             * I think this is just for the reconciled checkbox.
             * For the updating of a transaction from the popup, see TransactionPopupComponent
             */
            updateTransaction: function () {
                var data = TransactionsRepository.setFields(this.transaction);

                TotalsRepository.resetTotalChanges();

                helpers.put({
                    url: '/api/transactions/' + this.transaction.id,
                    data: data,
                    property: 'transactions',
                    message: 'Transaction updated',
                    redirectTo: this.redirectTo,
                    callback: function (response) {
                        TransactionsRepository.updateTransaction(this.transaction);
                        TotalsRepository.getSideBarTotals(this);
                        FilterRepository.getBasicFilterTotals(this);
                        //Todo: Remove the transaction from the JS transactions depending on the filter
                    }.bind(this)
                });
            },

            /**
             *
             * @param transaction
             */
            showAllocationPopup: function (transaction) {
                store.showAllocationPopup(transaction);
            },

            /**
             *
             * @param transaction
             */
            showTransactionPopup: function (transaction) {
                store.set(transaction, 'selectedTransaction');

                //save the original total so I can calculate
                // the difference if the total changes,
                // so I can remove the correct amount from savings if required.
                store.set(transaction.total, 'selectedTransaction.originalTotal');
                store.set(helpers.formatDurationToMinutes(transaction.minutes), 'selectedTransaction.duration');

                helpers.showPopup('transaction');
            }

        },
        props: {
            transaction: {
//                type: Object,
//                default: function () {
//                    return {
//                        account: {},
//                        budgets: [{name: ''}]
//                    }
//                }
            },
        },
        mounted: function () {
            //this.listen();
        }
    }
</script>
