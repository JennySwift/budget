
<table id ="flex-budget-info-table" class="table table-bordered">
    <caption class="bg-dark">Flex Budget Info</caption>
    <!-- table header -->
    <tr>
        <th class="tag">Tag</th>
        <th class="tooltipster" title="# percent of F/I">%</th>
        <th class="tooltipster" title="amount (% column % of F/I)">A</th>
        <th class="tooltipster" title="cumulative starting date">SD</th>
        <th class="tooltipster" title="cumulative month number">CMN</th>
        <th class="tooltipster" title="spent after starting date">-</th>
        <th class="tooltipster" title="received">+</th>
        <th class="tooltipster" title="remaining">R</th>
        <th>x</th>
    </tr>
    <!-- table content -->
    <tr ng-repeat="tag in totals.budget.FLB.tags" class="budget_info_ul">
        <td class="budget-tag">[[tag.name]]</td>
        <td class="percent">[[tag.flex_budget]]</td>
        <td class="amount">[[tag.calculated_budget]]</td>
        <td class="CSD">
            <span>[[tag.formatted_starting_date]]</span>
            <button ng-click="updateCSDSetup(tag)" class="edit">edit</button>
        </td>
        <td class="month-number">[[tag.CMN]]</td>
        <td class="spent">[[tag.spentAfterSD]]</td>
        <td class="received">[[tag.receivedAfterSD]]</td>
        <td class="remaining">[[tag.remaining]]</td>
        <td ng-click="removeBudget(tag)" class="pointer">x</td>
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