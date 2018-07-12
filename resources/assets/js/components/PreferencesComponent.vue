<template>
    <div id="preferences-page">

        <h1>Preferences</h1>

        <!--Colors-->
        <div>

            <h3 class="center">Choose your colours</h3>

            <div class="colors">

                <div class="color">

                    <div class="form-group">
                        <label for="income-color-picker">Income</label>

                        <div class="input-group">
                            <input
                                v-model="shared.me.preferences.colors.income"
                                type="color"
                                id="income-color-picker"
                                name="income-color-picker"
                                placeholder="income"
                                class="form-control"
                            >

                            <span class="input-group-btn">
                        <button
                            v-on:click="defaultColor('income', '#017d00')"
                            class="default-color-button btn btn-default">
                            Default
                        </button>
                    </span>
                        </div>

                    </div>

                </div>

                <div class="color">

                    <div class="form-group">
                        <label for="expense-color-picker">Expense</label>

                        <div class="input-group">
                            <input
                                v-model="shared.me.preferences.colors.expense"
                                type="color"
                                id="expense-color-picker"
                                name="expense-color-picker"
                                placeholder="expense"
                                class="form-control"
                            >
                            <span class="input-group-btn">
                        <button
                            v-on:click="defaultColor('expense', '#fb5e52')"
                            class="default-color-button btn btn-default">
                            Default
                        </button>
                    </span>
                        </div>


                    </div>

                </div>

                <div class="color">

                    <div class="form-group">
                        <label for="transfer-color-picker">Transfer</label>

                        <div class="input-group">
                            <input
                                v-model="shared.me.preferences.colors.transfer"
                                type="color"
                                id="transfer-color-picker"
                                name="transfer-color-picker"
                                placeholder="transfer"
                                class="form-control"
                            >
                            <span class="input-group-btn">
                        <button
                            v-on:click="defaultColor('transfer', '#fca700')"
                            class="default-color-button btn btn-default">
                            Default
                        </button>
                    </span>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <h3>New Transaction</h3>

        <div class="new-transaction">

            <div class="checkbox-container">
                <input
                    v-model="shared.me.preferences.clearFields"
                    id="preferences-clear-fields"
                    type="checkbox"
                >
                <label for="preferences-clear-fields">Clear fields upon entering new transaction</label>
            </div>

            <div class="checkbox-container">
                <input
                    v-model="shared.me.preferences.autocompleteDescription"
                    id="preferences-autocomplete-description"
                    type="checkbox"
                >
                <label for="preferences-autocomplete-description">Use description field to autocomplete new transaction</label>
            </div>

            <div class="checkbox-container">
                <input
                    v-model="shared.me.preferences.autocompleteMerchant"
                    id="preferences-autocomplete-merchant"
                    type="checkbox"
                >
                <label for="preferences-autocomplete-merchant">Use merchant field to autocomplete new transaction</label>
            </div>
        </div>

        <h3>Date Format</h3>

        <div class="formats">
            <div class="form-group">

                <div>
                    <label>DD/MM/YY</label>
                    <input v-model="shared.me.preferences.dateFormat" type="radio" value="DD/MM/YY">
                </div>

                <div>
                    <label>DD/MM/YYYY</label>
                    <input v-model="shared.me.preferences.dateFormat" type="radio" value="DD/MM/YYYY">
                </div>

            </div>
        </div>

        <h3>Totals to Show</h3>

        <div class="totals-to-show">
            <div class="form-group">

                <div class="checkbox-container">
                    <input
                        v-model="shared.me.preferences.show.totals.credit"
                        id="preferences-show-credit"
                        type="checkbox"
                    >
                    <label for="preferences-show-credit">Credit</label>
                </div>

                <div class="checkbox-container">
                    <input
                        v-model="shared.me.preferences.show.totals.remainingFixedBudget"
                        id="preferences-show-remainingFixedBudget"
                        type="checkbox"
                    >
                    <label for="preferences-show-remaining-fixed-budget">Remaining Fixed Budget</label>
                </div>

                <div class="checkbox-container">
                    <input
                        v-model="shared.me.preferences.show.totals.expensesWithoutBudget"
                        id="preferences-show-expenses-without-budget"
                        type="checkbox"
                    >
                    <label for="preferences-show-expenses-without-budget">Expenses without a fixed or flex budget</label>
                </div>

                <div class="checkbox-container">
                    <input
                        v-model="shared.me.preferences.show.totals.expensesWithFixedBudgetBeforeStartingDate"
                        id="preferences-show-expenses-with-fixed-budget-before-starting-date"
                        type="checkbox"
                    >
                    <label for="preferences-show-expenses-with-fixed-budget-before-starting-date">Expenses with fixed budget before starting date</label>
                </div>

                <div class="checkbox-container">
                    <input
                        v-model="shared.me.preferences.show.totals.expensesWithFixedBudgetAfterStartingDate"
                        id="preferences-show-expenses-with-fixed-budget-after-starting-date"
                        type="checkbox"
                    >
                    <label for="preferences-show-expenses-with-fixed-budget-after-starting-date">Expenses with fixed budget after starting date</label>
                </div>

                <div class="checkbox-container">
                    <input
                        v-model="shared.me.preferences.show.totals.expensesWithFlexBudgetBeforeStartingDate"
                        id="preferences-show-expenses-with-flex-budget-before-starting-date"
                        type="checkbox"
                    >
                    <label for="preferences-show-expenses-with-flex-budget-before-starting-date">Expenses with flex budget before starting date</label>
                </div>

                <div class="checkbox-container">
                    <input
                        v-model="shared.me.preferences.show.totals.expensesWithFlexBudgetAfterStartingDate"
                        id="preferences-show-expenses-with-flex-budget-after-starting-date"
                        type="checkbox"
                    >
                    <label for="preferences-show-expenses-with-flex-budget-after-starting-date">Expenses with flex budget after starting date</label>
                </div>

                <div class="checkbox-container">
                    <input
                        v-model="shared.me.preferences.show.totals.savings"
                        id="preferences-show-savings"
                        type="checkbox"
                    >
                    <label for="preferences-show-savings">Savings</label>
                </div>

                <div class="checkbox-container">
                    <input
                        v-model="shared.me.preferences.show.totals.remainingBalance"
                        id="preferences-show-remaining-balance"
                        type="checkbox"
                    >
                    <label for="preferences-show-remaining-balance">Remaining balance</label>
                </div>

                <div class="checkbox-container">
                    <input
                        v-model="shared.me.preferences.show.totals.debit"
                        id="preferences-show-debit"
                        type="checkbox"
                    >
                    <label for="preferences-show-debit">Debit</label>
                </div>

                <div class="checkbox-container">
                    <input
                        v-model="shared.me.preferences.show.totals.balance"
                        id="preferences-show-balance"
                        type="checkbox"
                    >
                    <label for="preferences-show-balance">Balance</label>
                </div>

                <div class="checkbox-container">
                    <input
                        v-model="shared.me.preferences.show.totals.reconciled"
                        id="preferences-show-reconciled"
                        type="checkbox"
                    >
                    <label for="preferences-show-reconciled">Reconciled</label>
                </div>

                <div class="checkbox-container">
                    <input
                        v-model="shared.me.preferences.show.totals.cumulativeFixedBudget"
                        id="preferences-show-cumulative-fixed-budget"
                        type="checkbox"
                    >
                    <label for="preferences-show-cumulative-fixed-budget">Cumulative fixed budget</label>
                </div>

            </div>
        </div>

        <div class="form-group">
            <button v-on:click="updatePreferences()" class="btn btn-success">Save</button>
        </div>

    </div>

</template>

<script>
    export default {
        data: function () {
            return {
                shared: store.state,
//                preferences: []
            };
        },
        components: {},
        methods: {

            /**
             *
             */
            updatePreferences: function () {
                var data = {
                    preferences: {
                        clearFields: this.shared.me.preferences.clearFields,
                        colors: {
                            income: this.shared.me.preferences.colors.income,
                            expense: this.shared.me.preferences.colors.expense,
                            transfer: this.shared.me.preferences.colors.transfer
                        },
                        dateFormat: this.shared.me.preferences.dateFormat,
                        autocompleteDescription: this.shared.me.preferences.autocompleteDescription,
                        autocompleteMerchant: this.shared.me.preferences.autocompleteMerchant,

                        show: {
                            totals: {
                                credit: this.shared.me.preferences.show.totals.credit,
                                remainingFixedBudget: this.shared.me.preferences.show.totals.remainingFixedBudget,
                                expensesWithoutBudget: this.shared.me.preferences.show.totals.expensesWithoutBudget,
                                expensesWithFixedBudgetBeforeStartingDate: this.shared.me.preferences.show.totals.expensesWithFixedBudgetBeforeStartingDate,
                                expensesWithFixedBudgetAfterStartingDate: this.shared.me.preferences.show.totals.expensesWithFixedBudgetAfterStartingDate,
                                expensesWithFlexBudgetBeforeStartingDate: this.shared.me.preferences.show.totals.expensesWithFlexBudgetBeforeStartingDate,
                                expensesWithFlexBudgetAfterStartingDate: this.shared.me.preferences.show.totals.expensesWithFlexBudgetAfterStartingDate,
                                savings: this.shared.me.preferences.show.totals.savings,
                                remainingBalance: this.shared.me.preferences.show.totals.remainingBalance,
                                debit: this.shared.me.preferences.show.totals.debit,
                                balance: this.shared.me.preferences.show.totals.balance,
                                reconciled: this.shared.me.preferences.show.totals.reconciled,
                                cumulativeFixedBudget: this.shared.me.preferences.show.totals.cumulativeFixedBudget
                            }
                        }
                    }
                };

                helpers.put({
                    url: '/api/users/' + this.shared.me.id,
                    data: data,
                    property: 'me',
                    message: 'Preferences updated',
                    redirectTo: this.redirectTo,
                });
            },

            /**
             *
             * @param type
             * @param defaultColor
             */
            defaultColor: function (type, defaultColor) {
                if (type === 'income') {
                    this.shared.me.preferences.colors.income = defaultColor;
                }
                else if (type === 'expense') {
                    this.shared.me.preferences.colors.expense = defaultColor;
                }
                else if (type === 'transfer') {
                    this.shared.me.preferences.colors.transfer = defaultColor;
                }
            },

//            oldCode () {
//                $scope.$watchCollection('colors', function (newValue) {
//                    $("#income-color-picker").val(newValue.income);
//                    $("#expense-color-picker").val(newValue.expense);
//                    $("#transfer-color-picker").val(newValue.transfer);
//                });
//            },

//            updateColors: function ($colors) {
//                var $url = 'api/update/colors';
//                var $description = 'colors';
//                var $data = {
//                    description: $description,
//                    colors: $colors
//                };
//
//                return $http.post($url, $data);
//            }
        },
        props: [
            //data to be received from parent
        ],
        mounted: function () {

        }
    }
</script>

<style lang="scss" type="text/scss">
    @import '../../sass/shared/variables';
    #preferences-page {
        margin: 20px;
        .clear-fields-container {
            display: flex;
            align-items: center;
            label {
                margin-bottom: 0;
                margin-right: 5px;
            }
        }
        //> * {
        //    margin: auto;
        //}
        //table {
        //    border-radius: none;
        //    td {
        //        border: none;
        //    }
        //    max-width: 312px;
        //}
        .colors {
            max-width: $preferencesWidth;
            margin: auto;
            .color {
                margin-bottom: 20px;
                //.flex {
                //    display: flex;
                //    button {
                //        margin-left: 15px;
                //    }
                //}
            }
        }
        .new-transaction {
            margin: auto;
            max-width: $preferencesWidth;
        }
        .formats {
            margin: auto;
            max-width: $preferencesWidth;
        }
        .totals-to-show {
            margin: auto;
            max-width: $preferencesWidth;
        }
        > .form-group {
            margin: auto;
            max-width: $preferencesWidth;
            button {
                width: 100%;
            }
        }
    }
</style>