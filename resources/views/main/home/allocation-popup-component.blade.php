<script id="allocation-popup-template" type="x-template">

    <div v-show="showPopup" v-cloak class="popup-outer">
        <div id="allocation-popup" class="popup-inner">

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
                <tr v-for="budget in transaction.budgets">

                    <td>
                        <div>
                                <span
                                        v-bind:class="{'tag-with-fixed-budget': budget.type === 'fixed', 'tag-with-flex-budget': budget.type === 'flex'}"
                                        class="label label-default"
                                >
                                    @{{ budget.name }}
                                </span>
                        </div>
                    </td>

                    <td>
                        <div class="editable">

                            <input
                                    v-if="budget.editingAllocatedFixed"
                                    v-model="budget.editedAllocatedFixed" type="text"
                                    v-on:keyup.13="updateAllocation('fixed', budget.editedAllocatedFixed, budget.id)"
                            >

                            <button
                                    v-if="!budget.editingAllocatedFixed"
                                    v-on:click="budget.editingAllocatedFixed = true"
                                    class="edit btn-default btn-xs">edit
                            </button>

                                <span
                                        v-if="!budget.editingAllocatedFixed"
                                >
                                    @{{ budget.pivot.allocated_fixed }}
                                </span>
                        </div>
                    </td>

                    <td>
                        <div class="editable">
                            <input
                                    v-if="budget.editingAllocatedPercent"
                                    v-model="budget.editedAllocatedPercent" type="text"
                                    v-on:keyup.13="updateAllocation('percent', budget.editedAllocatedPercent, budget.id)"
                            >

                            <button
                                    v-if="!budget.editingAllocatedPercent"
                                    v-on:click="budget.editingAllocatedPercent = true"
                                    class="edit btn btn-default btn-xs">edit
                            </button>

                                <span
                                        v-if="!budget.editingAllocatedPercent">
                                    @{{ budget.pivot.allocated_percent }}
                                </span>
                        </div>
                    </td>

                    <td>
                        <div>
                            <span>@{{ budget.pivot.calculated_allocation }}</span>
                        </div>
                    </td>

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
            <div class="center-contents">

                <div class="checkbox-container">
                    <input
                        v-model="transaction.allocated"
                        v-on:change="updateAllocationStatus()"
                        id="allocated-checkbox"
                        {{--:value="transaction.allocated"--}}
                        type="checkbox"
                    >
                    <label for="allocated-checkbox">Allocated</label>
                </div>

            </div>

            <div class="buttons">
                <button v-on:click="showPopup = false" class="close-modal">Close</button>
            </div>

        </div>
    </div>

</script>