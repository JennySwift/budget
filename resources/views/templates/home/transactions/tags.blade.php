<tr
    v-show="show.tags && transaction.budgets.length > 0"
    class="results-tag-location-container tag-location-container">

    <td colspan="9">
        <li
            v-on:click="removeResultTag(this)"
            v-repeat="budget in transaction.budgets"
            {{--v-class="{'tag-with-fixed-budget': tag.fixed_budget !== null, 'tag-with-flex-budget': tag.flex_budget !== null, 'tag-without-budget': tag.fixed_budget === null || tag.flex_budget === null}"--}}
            v-class="{'tag-with-fixed-budget': budget.type === 'fixed', 'tag-with-flex-budget': budget.type === 'flex', 'tag-without-budget': tag.fixed_budget === null || tag.flex_budget === null}"
            class="label label-default"
            {{--data-id="[[tag.id]]"--}}
            {{--data-allocated-percent="[[budget.allocated_percent]]"--}}
            {{--data-allocated-fixed="[[budget.allocated_fixed]]"--}}
            {{--data-allocated_fixed="[[budget.allocated_fixed]]"--}}>
            [[budget.name]]
            [[budget.pivot.calculated_allocation]]
        </li>
    </td>

</tr>