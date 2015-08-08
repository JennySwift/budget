
<table id ="flex-budget-info-table" class="table table-bordered">
    <caption class="bg-dark">Flex Budget Info</caption>
    <!-- table header -->
    <tr>
        <th class="tag">Tag</th>
        <th class="tooltipster" title="# percent of F/I">%</th>
        <th class="tooltipster" title="amount (% column % of F/I)">A</th>
        <th class="tooltipster" title="cumulative starting date">SD</th>
        <th class="tooltipster" title="cumulative month number">CMN</th>

        <th class="tooltipster" title="spent after starting date">
            <i class="fa fa-minus"></i>
        </th>

        <th class="tooltipster" title="received">
            <i class="fa fa-plus"></i>
        </th>

        <th class="tooltipster" title="remaining">R</th>

        <th>
            <i class="fa fa-times"></i>
        </th>
    </tr>
    <!-- table content -->
    <tr ng-repeat="tag in totals.budget.FLB.tags" class="budget_info_ul">
        <td ng-click="showBudgetPopup(tag, 'flex')" class="budget-tag pointer">[[tag.name]]</td>
        <td ng-click="showBudgetPopup(tag, 'flex')" class="percent pointer">[[tag.flex_budget]]</td>
        <td ng-click="showBudgetPopup(tag, 'flex')" class="amount pointer">[[tag.calculated_budget]]</td>
        <td ng-click="showBudgetPopup(tag, 'flex')" class="CSD pointer">
            <span>[[tag.formatted_starting_date]]</span>
        </td>
        <td ng-click="showBudgetPopup(tag, 'flex')" class="month-number pointer">[[tag.CMN]]</td>
        <td ng-click="showBudgetPopup(tag, 'flex')" class="spent pointer">[[tag.spentAfterSD]]</td>
        <td ng-click="showBudgetPopup(tag, 'flex')" class="received pointer">[[tag.receivedAfterSD]]</td>
        <td ng-click="showBudgetPopup(tag, 'flex')" class="remaining pointer">[[tag.remaining]]</td>
        <td>
            <button ng-click="removeBudget(tag)" class="btn btn-xs btn-danger">delete</button>
        </td>
    </tr>
    {{--unallocated--}}
    <tr id="flex-budget-unallocated" class="budget_info_ul">
        <td>unallocated</td>
        <td>[[totals.budget.FLB.unallocated.budget]]</td>
        <td>[[totals.budget.FLB.unallocated.calculated_budget]]</td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
        <td>[[totals.budget.FLB.unallocated.remaining]]</td>
        <td>-</td>
    </tr>
    <!-- flex budget totals -->
    <tr id="flex-budget-totals" class="budget_info_ul totals">
        <td>totals</td>
        <td>[[totals.budget.FLB.totals.budget]]</td>
        <td>[[totals.budget.FLB.totals.calculated_budget]]</td>
        <td>-</td>
        <td>-</td>
        <td>[[totals.budget.FLB.totals.spentAfterSD]]</td>
        <td>[[totals.budget.FLB.totals.receivedAfterSD]]</td>
        <td>[[totals.budget.FLB.totals.remaining]]</td>
        <td>-</td>
    </tr>
</table>