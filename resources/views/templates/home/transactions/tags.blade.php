<tr
    ng-show="show.tags && transaction.budgets.length > 0"
    class="results-tag-location-container tag-location-container">

    <td colspan="9">
        <li
            ng-click="removeResultTag(this)"
            ng-repeat="budget in transaction.budgets"
            {{--ng-class="{'tag-with-fixed-budget': tag.fixed_budget !== null, 'tag-with-flex-budget': tag.flex_budget !== null, 'tag-without-budget': tag.fixed_budget === null || tag.flex_budget === null}"--}}
            ng-class="{'tag-with-fixed-budget': budget.type === 'fixed', 'tag-with-flex-budget': budget.type === 'flex', 'tag-without-budget': tag.fixed_budget === null || tag.flex_budget === null}"
            class="label label-default"
            data-id="[[tag.id]]"
            data-allocated-percent="[[budget.allocated_percent]]"
            data-allocated-fixed="[[budget.allocated_fixed]]"
            data-allocated_fixed="[[budget.allocated_fixed]]">
            [[budget.name]]
        </li>
    </td>

</tr>