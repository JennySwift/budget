<template>
    <transition name="totals-wrapper">
        <div v-show="shared.show.totals" id="totals-wrapper">
            <transition name="totals">
                <div v-show="shared.show.totals" id="totals" class="totals">

                    <i v-if="totalsLoading" class="fa fa-spinner fa-pulse"></i>

                    <table class="totals-table table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Type</th>
                            <th>Total</th>
                            <th>Changed</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr
                            v-if="shared.me.preferences.show.totals.credit"
                            class="tooltipster"
                            title="credit"
                        >
                            <td>Credit:</td>
                            <td><span class="badge badge-success">{{ sideBarTotals.credit | numberFilter(2) }}</span></td>
                            <td><span v-if="totalChanges.credit">{{ totalChanges.credit | numberFilter(2) }}</span></td>
                        </tr>

                        <tr
                            v-if="shared.me.preferences.show.totals.remainingFixedBudget"
                            class="tooltipster"
                            title="remaining fixed budget (total of fixed budget info column R)"
                        >
                            <td>Remaining fixed budget:</td>
                            <td><span class="badge badge-danger">{{ sideBarTotals.remainingFixedBudget | numberFilter(2) }}</span></td>
                            <td><span v-if="totalChanges.remainingFixedBudget">{{ totalChanges.remainingFixedBudget | numberFilter(2) }}</span></td>
                        </tr>

                        <tr
                            v-if="shared.me.preferences.show.totals.expensesWithoutBudget"
                            class="tooltipster"
                            title="total of expense transactions that have no budget"
                        >
                            <td>Expenses with no fixed or flex budgets:</td>
                            <td><span class="badge badge-danger">{{ sideBarTotals.expensesWithoutBudget | numberFilter(2) }}</span></td>
                            <td><span v-if="totalChanges.expensesWithoutBudget">{{ totalChanges.expensesWithoutBudget | numberFilter(2) }}</span></td>
                        </tr>

                        <tr
                            v-if="shared.me.preferences.show.totals.expensesWithFixedBudgetBeforeStartingDate"
                            class="tooltipster"
                            title="total of allocation of tags of expense transactions that have a fixed budget before its starting date"
                        >
                            <td>Expenses with <b>fixed</b> budget <b>before</b> starting date:</td>
                            <td><span class="badge badge-danger">{{ sideBarTotals.expensesWithFixedBudgetBeforeStartingDate | numberFilter(2) }}</span></td>
                            <td><span v-if="totalChanges.expensesWithFixedBudgetBeforeStartingDate">{{ totalChanges.expensesWithFixedBudgetBeforeStartingDate | numberFilter(2) }}</span></td>
                        </tr>

                        <tr
                            v-if="shared.me.preferences.show.totals.expensesWithFixedBudgetAfterStartingDate"
                            class="tooltipster"
                            title="total of allocation of tags of expense transactions that have a fixed budget after its starting date"
                        >
                            <td>Expenses with <b>fixed</b> budget <b>after</b> starting date:</td>
                            <td><span id="total_income_span" class="badge badge-danger">{{ sideBarTotals.expensesWithFixedBudgetAfterStartingDate | numberFilter(2) }}</span></td>
                            <td><span v-if="totalChanges.expensesWithFixedBudgetAfterStartingDate">{{ totalChanges.expensesWithFixedBudgetAfterStartingDate | numberFilter(2) }}</span></td>
                        </tr>

                        <tr
                            v-if="shared.me.preferences.show.totals.expensesWithFlexBudgetBeforeStartingDate"
                            class="tooltipster"
                            title="total of allocation of tags of expense transactions that have a flex budget before its starting date"
                        >
                            <td>Expenses with <b>flex</b> budget <b>before</b> starting date:</td>
                            <td><span class="badge badge-danger">{{ sideBarTotals.expensesWithFlexBudgetBeforeStartingDate | numberFilter(2) }}</span></td>
                            <td><span v-if="totalChanges.expensesWithFlexBudgetBeforeStartingDate">{{ totalChanges.expensesWithFlexBudgetBeforeStartingDate | numberFilter(2) }}</span></td>
                        </tr>

                        <tr
                            v-if="shared.me.preferences.show.totals.savings"
                            class="tooltipster"
                            title="savings"
                        >
                            <td>Savings:</td>
                            <td><span class="badge badge-danger">{{ sideBarTotals.savings | numberFilter(2) }}</span></td>
                            <td><span v-if="totalChanges.savings">{{ totalChanges.savings | numberFilter(2) }}</span></td>
                        </tr>

                        <tr
                            v-if="shared.me.preferences.show.totals.remainingBalance"
                            class="tooltipster"
                            title="remaining balance without EFLB"
                        >
                            <td>Remaining balance:</td>
                            <td><span class="badge badge-danger">{{ sideBarTotals.remainingBalance | numberFilter(2) }}</span></td>
                            <td>
                                <span v-if="totalChanges.remainingBalance">{{ totalChanges.remainingBalance | numberFilter(2) }}</span>
                            </td>
                        </tr>

                        <tr
                            v-if="shared.me.preferences.show.totals.debit"
                            class="tooltipster"
                            title="debit"
                        >
                            <td>Debit:</td>
                            <td><span id="total_income_span" class="badge badge-danger">{{ sideBarTotals.debit |  numberFilter(2) }}</span></td>
                            <td><span v-if="totalChanges.debit">{{ totalChanges.debit |  numberFilter(2) }}</span></td>
                        </tr>

                        <tr
                            v-if="shared.me.preferences.show.totals.balance"
                            class="tooltipster"
                            title="balance (C - D)"
                        >
                            <td>Balance:</td>
                            <td><span id="total_income_span" class="badge badge-warning">{{ sideBarTotals.balance |  numberFilter(2) }}</span></td>
                            <td><span v-if="totalChanges.balance">{{ totalChanges.balance |  numberFilter(2) }}</span></td>
                        </tr>

                        <tr
                            v-if="shared.me.preferences.show.totals.reconciled"
                            class="tooltipster"
                            title="reconciled"
                        >
                            <td>Reconciled:</td>
                            <td><span id="total_income_span" class="badge badge-info">{{ sideBarTotals.reconciledSum |  numberFilter(2) }}</span></td>
                            <td><span v-if="totalChanges.reconciledSum">{{ totalChanges.reconciledSum |  numberFilter(2) }}</span></td>
                        </tr>

                        <tr
                            v-if="shared.me.preferences.show.totals.cumulativeFixedBudget"
                            class="tooltipster"
                            title="fixed budget (total of fixed budget info column C)"
                        >
                            <td>Cumulative fixed budget:</td>
                            <td><span id="total_income_span" class="badge badge-danger">{{ sideBarTotals.cumulativeFixedBudget |  numberFilter(2) }}</span></td>
                            <td><span v-if="totalChanges.cumulativeFixedBudget">{{ totalChanges.cumulativeFixedBudget |  numberFilter(2) }}</span></td>
                        </tr>

                        <tr
                            v-if="shared.me.preferences.show.totals.expensesWithFlexBudgetAfterStartingDate"
                            class="tooltipster"
                            title="total of allocation of tags of expense transactions that have a flex budget"
                        >
                            <td>Expenses with <b>flex</b> budget <b>after</b> starting date:</td>
                            <td><span id="total_income_span" class="badge badge-danger">{{ sideBarTotals.expensesWithFlexBudgetAfterStartingDate |  numberFilter(2) }}</span></td>
                            <td><span v-if="totalChanges.expensesWithFlexBudgetAfterStartingDate">{{ totalChanges.expensesWithFlexBudgetAfterStartingDate |  numberFilter(2) }}</span></td>
                        </tr>
                        </tbody>
                    </table>

                </div>
            </transition>
        </div>
    </transition>

</template>

<script>
    import TotalsRepository from '../repositories/TotalsRepository'
    import helpers from '../repositories/Helpers'

    export default {
        data: function () {
            return {
                totalsRepository: TotalsRepository.state,
                totalsLoading: false,
                shared: store.state
            };
        },
        components: {},
        computed: {
            sideBarTotals: function () {
                return this.totalsRepository.sideBarTotals;
            },
            totalChanges: function () {
                return this.totalsRepository.totalChanges;
            }
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
            }
        },
        methods: {


        },
        props: [],
        mounted: function () {

        }
    }
</script>

<style lang="scss" type="text/scss">
    @import '../../sass/variables';
    $totalsWidth: 354px;

    #totals-wrapper {
        width: $totalsWidth;
        &.totals-wrapper-enter-active {
            transition: all .5s ease-out;
        }
        &.totals-wrapper-leave-active {
            transition: all .4s ease-in;
        }
        &.totals-wrapper-enter, &.totals-wrapper-leave-to {
            width: 0;
        }

        #totals {
            width: $totalsWidth;
            z-index: $zIndex1;
            background: white;

            //animation
            animation: slideInLeft .5s;
            &.totals-leave-active {
                animation: slideOutLeft .3s;
            }

            .fa-pulse {
                position: absolute;
                top: 5px;
                left: 10px;
                font-size: 15px;
                color: $info;
            }

            .totals-table {
                //z-index: $zIndex1;
                //box-shadow: 3px 3px 10px #777;
                //margin-bottom: 20px;
                //padding-bottom: 10px;
                td {
                    text-align: right;
                    &:first-child {
                        max-width: 200px;
                    }
                    padding: 3px 5px;

                }
                //tr:not(:last-child) {
                //    td {
                //        border-bottom: 1px solid #777;
                //    }
                //}
            }
        }

    }
</style>
