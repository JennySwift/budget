<script id="allocation-popup-template" type="x-template">

    <div v-show="showPopup" v-cloak class="popup-outer">
        <div id="allocation-popup" class="popup-inner">

            <div v-if="!transaction.validAllocation" class="warning">
                <i class="fa fa-exclamation-circle"></i>
                <span>The budget allocations do not match the total for this transaction.</span>
            </div>

            <p class="width-100">The total for this transaction is <span class="bold">@{{ transaction.total }}</span>. You have more than one budget associated with this transaction. Specify what percentage of @{{  transaction.total }} you would like to be taken off each of the following budgets. Or, set a fixed amount to be taken off. </p>

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
                    v-for="budget in transaction.budgets"
                    is="budget-allocation"
                    :budget="budget"
                    :transaction="transaction"
                >
                </tr>

                <!-- totals -->
                <tr class="totals">
                    <td>totals</td>

                    <td>
                        <div>
                            <span>@{{ allocationTotals.fixedSum }}</span>
                        </div>
                    </td>

                    <td>
                        <div>
                            <span>@{{ allocationTotals.percentSum }}</span>
                        </div>
                    </td>

                    <td>
                        <div>
                            <span>@{{ allocationTotals.calculatedAllocationSum }}</span>
                        </div>
                    </td>

                </tr>

            </table>

            <!-- allocation checkbox -->
            {{--<div class="center-contents">--}}

                {{--<div class="checkbox-container">--}}
                    {{--<input--}}
                        {{--v-model="transaction.allocated"--}}
                        {{--v-on:change="updateAllocationStatus()"--}}
                        {{--id="allocated-checkbox"--}}
                        {{--:value="transaction.allocated"--}}
                        {{--type="checkbox"--}}
                    {{-->--}}
                    {{--<label for="allocated-checkbox">Allocated</label>--}}
                {{--</div>--}}

            {{--</div>--}}

            <div class="buttons">
                <button v-on:click="closePopup()" class="close-modal">Close</button>
            </div>

        </div>
    </div>

</script>