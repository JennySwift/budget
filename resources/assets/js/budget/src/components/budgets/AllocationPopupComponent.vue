<template>

    <popup
        popup-name="allocation"
        id="allocation-popup"
        :redirect-to="redirectTo"
    >
        <div slot="content">
            <div v-if="!shared.selectedTransactionForAllocation.validAllocation" class="warning">
                <i class="fa fa-exclamation-circle"></i>
                <span>The budget allocations do not match the total for this transaction.</span>
            </div>

            <p class="width-100">The total for this transaction is <span class="bold">{{ shared.selectedTransactionForAllocation.total }}</span>. You have more than one budget associated with this transaction. Specify what percentage of {{  shared.selectedTransactionForAllocation.total }} you would like to be taken off each of the following budgets. Or, set a fixed amount to be taken off. </p>

            <table class="table table-bordered">

                <!-- table header -->
                <tr>
                    <th>tag</th>
                    <th>allocated $</th>
                    <th>allocated %</th>
                    <th>calculated</th>
                </tr>

                <!-- table content -->
                <tr
                    v-for="budget in shared.selectedTransactionForAllocation.budgets"
                    is="budget-allocation"
                    :budget="budget"
                    :transaction="shared.selectedTransactionForAllocation"
                >
                </tr>

                <!-- totals -->
                <tr class="totals">
                    <td>totals</td>

                    <td>
                        <div>
                            <span>{{ shared.allocationTotals.fixedSum }}</span>
                        </div>
                    </td>

                    <td>
                        <div>
                            <span>{{ shared.allocationTotals.percentSum }}</span>
                        </div>
                    </td>

                    <td>
                        <div>
                            <span>{{ shared.allocationTotals.calculatedAllocationSum }}</span>
                        </div>
                    </td>

                </tr>

            </table>

            <!-- allocation checkbox -->
            <!--<div class="center-contents">-->

            <!--<div class="checkbox-container">-->
            <!--<input-->
            <!--v-model="shared.selectedTransactionForAllocation.allocated"-->
            <!--v-on:change="updateAllocationStatus()"-->
            <!--id="allocated-checkbox"-->
            <!--:value="shared.selectedTransactionForAllocation.allocated"-->
            <!--type="checkbox"-->
            <!-->
            <!--<label for="allocated-checkbox">Allocated</label>-->
            <!--</div>-->

            <!--</div>-->

        </div>

    </popup>

</template>

<script>
    import helpers from '../../repositories/Helpers'
    import FilterRepository from '../../repositories/FilterRepository'
    import BudgetAllocationComponent from './BudgetAllocationComponent.vue'
    import $ from 'jquery'
    export default {
        data: function () {
            return {
                shared: store.state,
                redirectTo: '/'
            };
        },
        components: {
            'budget-allocation': BudgetAllocationComponent
        },
        methods: {

            /**
             *
             */
            //updateAllocationStatus: function () {
            //    $.event.trigger('show-loading');
            //
            //    var data = {
            //        allocated: helpers.convertBooleanToInteger(this.shared.selectedTransactionForAllocation.allocated)
            //    };
            //
            //    this.$http.put('/api/transactions/' + this.shared.selectedTransactionForAllocation.id, data, function (response) {
            //        $.event.trigger('provide-feedback', ['Allocation updated', 'success']);
            //        $.event.trigger('hide-loading');
            //    })
            //    .error(function (response) {
            //        helpers.handleResponseError(response);
            //    });
            //},
        },
        props: [
            //data to be received from parent
        ],
        mounted: function () {

        },
    }
</script>