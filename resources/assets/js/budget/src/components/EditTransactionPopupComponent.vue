<template>
    <div
        v-show="showPopup"
        v-on:click="closePopup($event)"
        class="popup-outer"
    >

        <div id="edit-transaction" class="popup-inner">

            <div class="form-group">
                <label for="selected-transaction-date">Date</label>
                <input
                    v-model="selectedTransaction.userDate"
                    type="text"
                    id="selected-transaction-date"
                    name="selected-transaction-date"
                    placeholder="date"
                    class="form-control"
                >
            </div>

            <div class="form-group">
                <label for="selected-transaction-description">Description</label>
                <input
                    v-model="selectedTransaction.description"
                    type="text"
                    id="selected-transaction-description"
                    name="selected-transaction-description"
                    placeholder="description"
                    class="form-control"
                >
            </div>

            <div class="form-group">
                <label for="selected-transaction-merchant">Merchant</label>
                <input
                    v-model="selectedTransaction.merchant"
                    type="text"
                    id="selected-transaction-merchant"
                    name="selected-transaction-merchant"
                    placeholder="merchant"
                    class="form-control"
                >
            </div>

            <div class="form-group">
                <label for="selected-transaction-total">Total</label>
                <input
                    v-model="selectedTransaction.total"
                    type="text"
                    id="selected-transaction-total"
                    name="selected-transaction-total"
                    placeholder="$"
                    class="form-control"
                >
            </div>

            <div class="form-group">
                <label for="selected-transaction-type">Type</label>

                <select
                    v-model="selectedTransaction.type"
                    id="selected-transaction-type"
                    class="form-control"
                >
                    <option
                        v-for="type in types"
                        v-bind:value="type.value"
                    >
                        {{ type.name }}
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label for="selected-transaction-account">Account</label>

                <select
                    v-model="selectedTransaction.account"
                    id="selected-transaction-account"
                    class="form-control"
                >
                    <option
                        v-for="account in shared.accounts"
                        v-bind:value="account"
                    >
                        {{ account.name }}
                    </option>
                </select>
            </div>

            <!--<div v-show="selectedTransaction.type === 'transfer'" class="col-xs-12">-->
            <!--<select-->
            <!--v-model="selectedTransaction.from_account"-->
            <!--id="selected-transaction-from-account"-->
            <!--class="form-control"-->
            <!-->-->
            <!--<option-->
            <!--v-for="account in accounts"-->
            <!--v-bind:value="account"-->
            <!-->-->
            <!--{{ account.name }}-->
            <!--</option>-->
            <!--</select>-->
            <!--</div>-->

            <!--<div v-show="selectedTransaction.type === 'transfer'" class="col-xs-12">-->
            <!--<select-->
            <!--v-model="selectedTransaction.to_account"-->
            <!--id="selected-transaction-to-account"-->
            <!--class="form-control"-->
            <!-->-->
            <!--<option-->
            <!--v-for="account in accounts"-->
            <!--v-bind:value="account"-->
            <!-->-->
            <!--{{ account.name }}-->
            <!--</option>-->
            <!--</select>-->
            <!--</div>-->

            <div class="form-group">
                <label for="selected-transaction-duration">Duration</label>
                <!--				<div>{{ selectedTransaction.minutes }}</div>-->
                <input
                    v-model="selectedTransaction.duration"
                    type="text"
                    id="selected-transaction-duration"
                    name="selected-transaction-duration"
                    placeholder="duration"
                    class="form-control"
                >
            </div>

            <div class="form-group reconciled">
                <label for="selected-transaction-reconciled">Reconciled</label>
                <input v-model="selectedTransaction.reconciled" type="checkbox">
            </div>

            <budget-autocomplete
                v-if="selectedTransaction.type !== 'transfer'"
                :chosen-budgets.sync="selectedTransaction.budgets"
                :budgets="shared.budgets"
                multiple-budgets="true"
            >
            </budget-autocomplete>

            <div class="buttons">
                <button v-on:click="showPopup = false" class="btn btn-default">Cancel</button>
                <button v-on:click="deleteTransaction(transaction)" class="btn btn-danger">Delete</button>
                <button v-on:click="updateTransaction()" class="btn btn-success">Save</button>
            </div>

        </div>
    </div>

</template>

<script>
    import TransactionsRepository from '../repositories/TransactionsRepository'
    import FilterRepository from '../repositories/FilterRepository'
    import TotalsRepository from '../repositories/TotalsRepository'
    import helpers from '../repositories/Helpers'
    import $ from 'jquery'
    export default {
        data: function () {
            return {
                showPopup: false,
                selectedTransaction: {},
                types: [
                    {value: 'income', name: 'credit'},
                    {value: 'expense', name: 'debit'},
                    {value: 'transfer', name: 'transfer'},
                ],
                shared: store.state
            };
        },
        components: {},
        methods: {

            /**
             *
             */
            updateTransaction: function () {
                var data = TransactionsRepository.setFields(this.selectedTransaction);

                TotalsRepository.resetTotalChanges();

                helpers.put({
                    url: '/api/transactions/' + this.selectedTransaction.id,
                    data: data,
                    property: 'transactions',
                    message: 'Transaction updated',
                    redirectTo: this.redirectTo,
                    callback: function (response) {
                        TransactionsRepository.updateTransaction(response);
                        TotalsRepository.getSideBarTotals(this);
                        FilterRepository.getBasicFilterTotals(this);
                        FilterRepository.runFilter(this);
                        //Todo: Remove the transaction from the JS transactions depending on the filter
                    }.bind(this)
                });
            },

            /**
             *
             */
            deleteTransaction: function () {
                helpers.delete({
                    url: '/api/transactions/' + this.selectedTransaction.id,
                    array: 'transactions',
                    itemToDelete: this.transaction,
                    message: 'Transaction deleted',
                    redirectTo: this.redirectTo,
                    callback: function () {
                        TransactionsRepository.deleteTransaction(this.selectedTransaction);
                        $.event.trigger('clear-total-changes');
                        TotalsRepository.getSideBarTotals(this);
                        FilterRepository.getBasicFilterTotals(this);
                    }
                });
            },

            /**
             *
             */
            closePopup: function ($event) {
                helpers.closePopup($event, this);
            },

            /**
             *
             */
            listen: function () {
                var that = this;
                $(document).on('show-edit-transaction-popup', function (event, transaction) {
                    that.selectedTransaction = transaction;

                    //save the original total so I can calculate
                    // the difference if the total changes,
                    // so I can remove the correct amount from savings if required.
                    that.selectedTransaction.originalTotal = that.selectedTransaction.total;
                    that.selectedTransaction.duration = HelpersRepository.formatDurationToMinutes(that.selectedTransaction.minutes);

                    that.showPopup = true;
                });
            }
        },
        props: [],
        mounted: function () {
            this.listen();
        }
    }
</script>