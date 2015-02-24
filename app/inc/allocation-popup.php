<!-- ==============================allocation============================== -->

<div ng-show="show.allocation_popup" class="popup-outer">
    <div id="allocation-popup" class="popup-inner">
        <p class="width-100">The total for this transaction is <span class="bold">{{allocation_popup_transaction.total}}</span>. You have more than one budget associated with this transaction. Specify what percentage of {{allocation_popup_transaction.total}} you would like to be taken off each of the following budgets. Or, set a fixed amount to be taken off. </p>
        
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
                <tr ng-repeat="tag in allocation_popup_transaction.tags" ng-if="tag.fixed_budget || tag.flex_budget">
                    
                    <td>
                        <div>
                            <span>{{tag.name}}</span>
                        </div>
                    </td>

                    <td>
                        <div class="editable">
                            <input ng-if="tag.editing_allocated_fixed" ng-keyup="updateAllocation($event.keyCode, 'fixed', tag.edited_allocated_fixed, tag.id)" ng-model="tag.edited_allocated_fixed" type="text">
                            <button ng-if="!tag.editing_allocated_fixed" ng-click="tag.editing_allocated_fixed = true" class="edit">edit</button>
                            <span ng-if="!tag.editing_allocated_fixed">{{tag.allocated_fixed}}</span>
                        </div>
                    </td>

                    <td>
                        <div class="editable">
                            <input ng-if="tag.editing_allocated_percent" ng-keyup="updateAllocation($event.keyCode, 'percent', tag.edited_allocated_percent, tag.id)" ng-model="tag.edited_allocated_percent" type="text">
                            <button ng-if="!tag.editing_allocated_percent" ng-click="tag.editing_allocated_percent = true" class="edit">edit</button>
                            <span ng-if="!tag.editing_allocated_percent">{{tag.allocated_percent}}</span>
                        </div>
                    </td>
            
                    <td>
                        <div>
                            <span>{{tag.calculated_allocation}}</span>
                        </div>
                    </td>
            
                </tr>
                <!-- totals -->
                <tr class="totals">
                    <td>totals</td>

                    <td>
                        <div>
                            <span>{{allocation_popup_transaction.allocation_totals.fixed_sum}}</span>
                        </div>
                    </td>

                    <td>
                        <div>
                            <span>{{allocation_popup_transaction.allocation_totals.percent_sum}}</span>
                        </div>
                    </td>
        
                    <td>
                        <div>
                            <span>{{allocation_popup_transaction.allocation_totals.calculated_allocation_sum}}</span>
                        </div>
                    </td>

                </tr>
            
            </table>
        </div>

        <!-- allocation checkbox -->
        <div class="center-contents">
            <div class="checkbox-wrapper">
                <label for="allocate-checkbox">allocated</label>
                <input ng-model="allocation_popup_transaction.allocation" ng-change="updateAllocationStatus()" type="checkbox">
            </div>
        </div>
        <!-- close button -->
        <button ng-click="show.allocation_popup = false" class="close-modal">Close</button>
    </div>
</div>