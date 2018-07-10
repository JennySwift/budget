<template>

    <popup
        popup-name="transaction"
        id="transaction-popup"
        :save="updateTransaction"
        :destroy="deleteTransaction"
        :redirect-to="redirectTo"
    >
        <div slot="content">
            <div class="form-group">
                <label for="selected-transaction-date">Date</label>
                <input
                    v-model="shared.selectedTransaction.userDate"
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
                    v-model="shared.selectedTransaction.description"
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
                    v-model="shared.selectedTransaction.merchant"
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
                    v-model="shared.selectedTransaction.total"
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
                    v-model="shared.selectedTransaction.type"
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
                    v-model="shared.selectedTransaction.account"
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
            <!-->
            <!--<option-->
            <!--v-for="account in accounts"-->
            <!--v-bind:value="account"-->
            <!-->
            <!--{{ account.name }}-->
            <!--</option>-->
            <!--</select>-->
            <!--</div>-->

            <!--<div v-show="selectedTransaction.type === 'transfer'" class="col-xs-12">-->
            <!--<select-->
            <!--v-model="selectedTransaction.to_account"-->
            <!--id="selected-transaction-to-account"-->
            <!--class="form-control"-->
            <!-->
            <!--<option-->
            <!--v-for="account in accounts"-->
            <!--v-bind:value="account"-->
            <!-->
            <!--{{ account.name }}-->
            <!--</option>-->
            <!--</select>-->
            <!--</div>-->

            <div class="form-group">
                <label for="selected-transaction-duration">Duration</label>
                <!--				<div>{{ selectedTransaction.minutes }}</div>-->
                <input
                    v-model="shared.selectedTransaction.duration"
                    type="text"
                    id="selected-transaction-duration"
                    name="selected-transaction-duration"
                    placeholder="duration"
                    class="form-control"
                >
            </div>

            <div class="form-group reconciled">
                <label for="selected-transaction-reconciled">Reconciled</label>
                <input v-model="shared.selectedTransaction.reconciled" type="checkbox">
            </div>

            <autocomplete
                v-if="shared.selectedTransaction.type !== 'transfer'"
                autocomplete-id="transaction-budgets-autocomplete"
                input-id="transaction-budgets-input"
                :unfiltered-options="shared.budgets"
                prop="name"
                multiple-selections="true"
                :chosen-options="shared.selectedTransaction.budgets"
                :function-on-enter="updateTransaction"
            >
            </autocomplete>

        </div>

    </popup>

</template>

<script>
    import TransactionsRepository from '../../repositories/TransactionsRepository'
    import FilterRepository from '../../repositories/FilterRepository'
    import TotalsRepository from '../../repositories/TotalsRepository'
    import helpers from '../../repositories/helpers/Helpers'
    import $ from 'jquery'
    export default {
        data: function () {
            return {
                types: [
                    {value: 'income', name: 'credit'},
                    {value: 'expense', name: 'debit'},
                    {value: 'transfer', name: 'transfer'},
                ],
                shared: store.state,
                redirectTo: '/'
            };
        },
        components: {},
        methods: {

            /**
             *
             */
            updateTransaction: function () {
                var data = TransactionsRepository.setFields(this.shared.selectedTransaction);

                TotalsRepository.resetTotalChanges();

                helpers.put({
                    url: '/api/transactions/' + this.shared.selectedTransaction.id,
                    data: data,
                    property: 'transactions',
                    message: 'Transaction updated',
                    redirectTo: this.redirectTo,
                    callback: function (response) {
                        store.update(response, 'transactions');
                        TotalsRepository.getSideBarTotals(this);
                        FilterRepository.getBasicFilterTotals(this);
                        FilterRepository.runFilter(this);

                        if (response.multipleBudgets && !response.validAllocation) {
                            store.showAllocationPopup(this.shared.selectedTransaction);
                        }
                        //Todo: Remove the transaction from the JS transactions depending on the filter
                    }.bind(this)
                });
            },

            /**
             *
             */
            deleteTransaction: function () {
                helpers.delete({
                    url: '/api/transactions/' + this.shared.selectedTransaction.id,
                    array: 'transactions',
                    itemToDelete: this.transaction,
                    message: 'Transaction deleted',
                    redirectTo: this.redirectTo,
                    callback: function () {
                        TransactionsRepository.deleteTransaction(this.shared.selectedTransaction);
                        $.event.trigger('clear-total-changes');
                        TotalsRepository.getSideBarTotals(this);
                        FilterRepository.getBasicFilterTotals(this);
                    }
                });
            },

            optionChosen: function (option, inputId) {
                switch(inputId) {
                    case 'transaction-budgets-input':
                        store.add(option, 'selectedTransaction.budgets');
                        break;
                }
            },
            chosenOptionRemoved: function (option, inputId) {
                switch(inputId) {
                    case 'transaction-budgets-input':
                        store.delete(option, 'selectedTransaction.budgets');
                        break;
                }
            },
        },
        props: [],
        mounted: function () {

        },
        created: function () {
            this.$bus.$on('autocomplete-option-chosen', this.optionChosen);
            this.$bus.$on('autocomplete-chosen-option-removed', this.chosenOptionRemoved);
        }
    }
</script>

<style lang="scss" type="text/scss">
    #transaction-popup {
        .reconciled {
            display: flex;
            align-items: center;
            label {
                margin-bottom: 0;
                margin-right: 2px;
            }
        }
    }
</style>