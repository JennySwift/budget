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
                            <totals-row field="credit" label="credit">
                                <template>Credit</template>
                            </totals-row>

                            <totals-row field="remainingFixedBudget" title="total of fixed budget info column R">
                                <template>Remaining fixed budget</template>
                            </totals-row>

                            <totals-row field="expensesWithoutBudget" color="red" title="Expenses with no fixed or flex budgets">
                                <template>Expenses with no fixed or flex budgets</template>
                            </totals-row>

                            <totals-row field="expensesWithFixedBudgetBeforeStartingDate" color="red" title="total of allocation of tags of expense transactions that have a fixed budget before its starting date">
                                <template>Expenses with <b>fixed</b> budget <b>before</b> starting date</template>
                            </totals-row>

                            <totals-row field="expensesWithFixedBudgetAfterStartingDate" color="red" title="total of allocation of tags of expense transactions that have a fixed budget after its starting date">
                                <template>Expenses with <b>fixed</b> budget <b>after</b> starting date</template>
                            </totals-row>

                            <totals-row field="expensesWithFlexBudgetBeforeStartingDate" color="red" title="total of allocation of tags of expense transactions that have a flex budget before its starting date">
                                <template>Expenses with <b>flex</b> budget <b>before</b> starting date</template>
                            </totals-row>

                            <totals-row field="savings">
                                <template>Savings</template>
                            </totals-row>

                            <totals-row field="remainingBalance" title="remaining balance without EFLB">
                                <template>Remaining balance</template>
                            </totals-row>

                            <totals-row field="debit" color="red">
                                <template>Debit</template>
                            </totals-row>

                            <totals-row field="balance" title="balance (C - D)">
                                <template>Balance</template>
                            </totals-row>

                            <!--Todo: This field needs to be "reconciledSum" not "reconciled"-->
                            <totals-row field="reconciled">
                                <template>Reconciled</template>
                            </totals-row>

                            <totals-row field="cumulativeFixedBudget" title="fixed budget (total of fixed budget info column C)">
                                <template>Cumulative fixed budget</template>
                            </totals-row>

                            <totals-row field="expensesWithFlexBudgetAfterStartingDate" color="red" title="total of allocation of tags of expense transactions that have a flex budget">
                                <template>Expenses with <b>flex</b> budget <b>after</b> starting date</template>
                            </totals-row>
                        </tbody>
                    </table>

                </div>
            </transition>
        </div>
    </transition>

</template>

<script>
    import TotalsRepository from '../repositories/TotalsRepository'
    import helpers from '../repositories/helpers/Helpers'
    import TotalsRow from './shared/TotalsRowComponent'

    export default {
        data: function () {
            return {
                totalsRepository: TotalsRepository.state,
                totalsLoading: false,
                shared: store.state
            };
        },
        components: {
            'totals-row': TotalsRow
        },
        methods: {


        },
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
