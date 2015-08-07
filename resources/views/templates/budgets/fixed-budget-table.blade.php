<table id="fixed-budget-info-table" class="table table-bordered">
    <caption class="bg-dark">Fixed Budget Info</caption>
    <!-- table header -->
    <tr>
        <th class="tag">Tag</th>
        <th class="tooltipster" title="amount">A</th>
        <th class="tooltipster" title="cumulative starting date">SD</th>
        <th class="tooltipster" title="cumulative month number">CMN</th>
        <th class="tooltipster" title="cumulative (amount * cumulative month number)">C</th>

        <th class="tooltipster" title="spent before cumulative starting date">
            <i class="fa fa-minus"></i>
        </th>

        <th class="tooltipster" title="spent since cumulative starting date">
            <i class="fa fa-minus"></i>
        </th>

        <th class="tooltipster" title="received since cumulative starting date">
            <i class="fa fa-plus"></i>
        </th>

        <th class="tooltipster" title="remaining  (cumulative + spent + received)">R</th>

        <th>
            <i class="fa fa-times"></i>
        </th>
    </tr>

    <!-- table content -->
    <tr ng-repeat="tag in totals.budget.FB.tags" class="budget_info_ul">

        <td class="budget-tag">[[tag.name]]</td>

        <td class="amount right">[[tag.fixed_budget]]</td>

        <td class="CSD">
            <span>[[tag.formatted_starting_date]]</span>
            <button ng-click="updateCSDSetup(tag)" class="edit">edit</button>
        </td>

        <td class="month-number">[[tag.CMN]]</td>

        <td class="cumulative">[[tag.cumulative]]</td>

        <td class="spent">
            <div>[[tag.spentBeforeSD]]</div>
        </td>

        <td class="spent">
            <div>[[tag.spentAfterSD]]</div>
        </td>

        <td class="received">[[tag.receivedAfterSD]]</td>

        <td class="remaining">[[tag.remaining]]</td>

        <td>
            <button ng-click="removeBudget(tag)" class="btn btn-xs btn-danger">delete</button>
        </td>

    </tr>

    <!-- fixed budget totals -->
    <tr id="fixed-budget-totals" class="budget_info_ul totals">
        <td>totals</td>
        <td>[[totals.budget.FB.totals.budget]]</td>
        <td>-</td>
        <td>-</td>
        <td>[[totals.budget.FB.totals.cumulative]]</td>
        <td>[[totals.budget.FB.totals.spentBeforeSD]]</td>
        <td>[[totals.budget.FB.totals.spentAfterSD]]</td>
        <td>[[totals.budget.FB.totals.receivedAfterSD]]</td>
        <td>[[totals.budget.FB.totals.remaining]]</td>
        <td>-</td>
    </tr>

</table>