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

        <td ng-click="showBudgetPopup(tag, 'fixed')" class="budget-tag pointer">[[tag.name]]</td>

        <td ng-click="showBudgetPopup(tag, 'fixed')" class="amount right pointer">[[tag.fixed_budget]]</td>

        <td ng-click="showBudgetPopup(tag, 'fixed')" class="CSD pointer">
            <span>[[tag.formatted_starting_date]]</span>
        </td>

        <td ng-click="showBudgetPopup(tag, 'fixed')" class="month-number pointer">[[tag.CMN]]</td>

        <td ng-click="showBudgetPopup(tag, 'fixed')" class="cumulative pointer">[[tag.cumulative]]</td>

        <td ng-click="showBudgetPopup(tag, 'fixed')" class="spent pointer">
            <div>[[tag.spentBeforeSD]]</div>
        </td>

        <td ng-click="showBudgetPopup(tag, 'fixed')" class="spent pointer">
            <div>[[tag.spentAfterSD]]</div>
        </td>

        <td ng-click="showBudgetPopup(tag, 'fixed')" class="received pointer">[[tag.receivedAfterSD]]</td>

        <td ng-click="showBudgetPopup(tag, 'fixed')" class="remaining pointer">[[tag.remaining]]</td>

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