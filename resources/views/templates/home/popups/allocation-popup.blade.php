<!-- ==============================allocation============================== -->

<div ng-show="show.allocationPopup" ng-cloak class="popup-outer">
    <div id="allocation-popup" class="popup-inner">
        <p class="width-100">The total for this transaction is <span class="bold">[[allocationPopup.total]]</span>. You have more than one budget associated with this transaction. Specify what percentage of [[allocation_popup.total]] you would like to be taken off each of the following budgets. Or, set a fixed amount to be taken off. </p>
        
        <div id="allocation-table-container">
            <table class="table table-bordered">

                <!-- table header -->
                <tr>
                    <th>tag</th>
                    <th>allocated $</th>
                    <th>allocated %</th>
                    <th>calculated</th>
                </tr>

                <!-- table content -->
                <tr ng-repeat="budget in allocationPopup.budgets">
                    
                    <td>
                        <div>
                            <span ng-class="{'tag-with-fixed-budget': budget.type === 'fixed', 'tag-with-flex-budget': budget.type === 'flex'}" class="label label-default">[[budget.name]]</span>
                        </div>
                    </td>

                    <td>
                        <div class="editable">

                            <input
                                ng-if="budget.editingAllocatedFixed"
                                ng-keyup="updateAllocation($event.keyCode, 'fixed', budget.editedAllocatedFixed, budget.id)"
                                ng-model="budget.editedAllocatedFixed" type="text">

                            <button
                                ng-if="!budget.editingAllocatedFixed"
                                ng-click="budget.editingAllocatedFixed = true"
                                class="edit">edit
                            </button>

                            <span
                                ng-if="!budget.editingAllocatedFixed">
                                [[budget.pivot.allocated_fixed]]
                            </span>
                        </div>
                    </td>

                    <td>
                        <div class="editable">
                            <input
                                ng-if="budget.editingAllocatedPercent"
                                ng-keyup="updateAllocation($event.keyCode, 'percent', budget.editedAllocatedPercent, budget.id)"
                                ng-model="budget.editedAllocatedPercent" type="text">

                            <button
                                ng-if="!budget.editingAllocatedPercent"
                                ng-click="budget.editingAllocatedPercent = true"
                                class="edit">edit
                            </button>

                            <span
                                ng-if="!budget.editingAllocatedPercent">
                                [[budget.pivot.allocated_percent]]
                            </span>
                        </div>
                    </td>
            
                    <td>
                        <div>
                            <span>[[budget.pivot.calculated_allocation]]</span>
                        </div>
                    </td>
            
                </tr>

                <!-- totals -->
                <tr class="totals">
                    <td>totals</td>

                    <td>
                        <div>
                            <span>[[allocationPopup.totals.fixedSum]]</span>
                        </div>
                    </td>

                    <td>
                        <div>
                            <span>[[allocationPopup.totals.percentSum]]</span>
                        </div>
                    </td>
        
                    <td>
                        <div>
                            <span>[[allocationPopup.totals.calculatedAllocationSum]]</span>
                        </div>
                    </td>

                </tr>
            
            </table>
        </div>

        <!-- allocation checkbox -->
        <div class="center-contents">
            <div class="checkbox-wrapper">
                <label for="allocate-checkbox">allocated</label>
                <input ng-model="allocationPopup.allocated" ng-change="updateAllocationStatus()" type="checkbox">
            </div>
        </div>

        <!-- close button -->
        <button ng-click="show.allocationPopup = false" class="close-modal">Close</button>
    </div>
</div>