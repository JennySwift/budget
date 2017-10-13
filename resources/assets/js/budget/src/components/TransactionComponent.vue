<template>
    <tbody>

    <tr class="results_inner_div">

        <td
            v-show="transactionPropertiesToShow.date"
            v-on:click="showEditTransactionPopup(transaction)"
            class="pointer"
        >
            @{{ transaction.date | formatDateForUser}}
        </td>

        <td
            v-show="transactionPropertiesToShow.description"
            v-on:click="showEditTransactionPopup(transaction)"
            class="description pointer"
        >
            <div>
                <div>@{{ transaction.description }}</div>
            </div>

            <div v-if="transaction.description" class="tooltip-container">
                <div class="tooltip">@{{ transaction.description }}</div>
            </div>
        </td>

        <td
            v-show="transactionPropertiesToShow.merchant"
            v-on:click="showEditTransactionPopup(transaction)"
            class="merchant pointer"
        >
            <div>
                <div>@{{ transaction.merchant }}</div>
            </div>

            <div v-if="transaction.merchant" class="tooltip-container">
                <div class="tooltip">@{{ transaction.merchant }}</div>
            </div>
        </td>

        <td
            v-show="transactionPropertiesToShow.total"
            v-on:click="showEditTransactionPopup(transaction)"
            class="pointer"
        >
            <!--<span class="badge badge-@{{ transaction.type }}">@{{ transaction.total }}</span>-->
            @{{ transaction.total | numberFilter 2 }}
        </td>

        <td
            v-show="transactionPropertiesToShow.account"
            v-on:click="showEditTransactionPopup(transaction)"
            class="max-width-md pointer"
        >
            @{{ transaction.account.name }}
        </td>

        <td
            v-show="transactionPropertiesToShow.duration"
            v-on:click="showEditTransactionPopup(transaction)"
            class="pointer"
        >
            <span v-if="transaction.minutes">@{{ transaction.minutes | formatDurationFilter }}</span>
        </td>

        <td v-show="transactionPropertiesToShow.reconciled"
        >
            <input v-model="transaction.reconciled" v-on:change="updateTransaction()" type="checkbox">
        </td>

        <td v-show="transactionPropertiesToShow.allocated">
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

    </tr>

    @include('main.home.transactions.budgets')

    </tbody>

</template>

<script>
    export default {
        data: function () {
            return {

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
                return HelpersRepository.formatDurationToHoursAndMinutes(minutes);
            },

            /**
             *
             * @param number
             * @param howManyDecimals
             * @returns {Number}
             */
            numberFilter: function (number, howManyDecimals) {
                return HelpersRepository.numberFilter(number, howManyDecimals);
            },

            /**
             *
             * @param date
             * @returns {*|String}
             */
            formatDateForUser: function (date) {
                return HelpersRepository.formatDateForUser(date, me.preferences.dateFormat);
            }
        },
        methods: {

            /**
             * I think this is just for the reconciled checkbox.
             * For the updating of a transaction from the popup, see EditTransactionPopupComponent
             */
            updateTransaction: function () {
                $.event.trigger('show-loading');

                var data = TransactionsRepository.setFields(this.transaction);

                TotalsRepository.resetTotalChanges();

                this.$http.put('/api/transactions/' + this.transaction.id, data, function (response) {
                    TransactionsRepository.updateTransaction(this.transaction);
                    TotalsRepository.getSideBarTotals(this);
                    FilterRepository.getBasicFilterTotals(this);
                    //Todo: Remove the transaction from the JS transactions depending on the filter
                    $.event.trigger('provide-feedback', ['Transaction updated', 'success']);
                    $.event.trigger('hide-loading');
                })
                    .error(function (response) {
                        HelpersRepository.handleResponseError(response);
                    });
            },

            /**
             *
             * @param transaction
             */
            showAllocationPopup: function (transaction) {
                $.event.trigger('show-allocation-popup', [transaction]);
            },

            /**
             *
             * @param transaction
             */
            showEditTransactionPopup: function (transaction) {
                $.event.trigger('show-edit-transaction-popup', [transaction]);
            }

        },
        props: [
            'transactions',
            'transaction',
            'transactionPropertiesToShow'
        ],
        mounted: function () {
            //this.listen();
        }
    }
</script>
