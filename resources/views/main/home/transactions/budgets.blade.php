<tr
    v-show="showBudgets && transaction.budgets.length > 0"
    class="results-tag-location-container tag-location-container">
    
    <td colspan="9">
        <li
            v-on:click="removeResultTag(this)"
            v-for="budget in transaction.budgets"
            v-bind:class="{
                'tag-with-fixed-budget': budget.type === 'fixed',
                'tag-with-flex-budget': budget.type === 'flex',
                'tag-without-budget': budget.type === 'unassigned'
            }"
            class="label label-default"
            {{--data-id="@{{ tag.id }}"--}}
            {{--data-allocated-percent="@{{ budget.allocated_percent }}"--}}
            {{--data-allocated-fixed="@{{ budget.allocated_fixed }}"--}}
            {{--data-allocated_fixed="@{{ budget.allocated_fixed }}"--}}>
            @{{ budget.name }}
            @{{ budget.pivot.calculated_allocation }}
        </li>
    </td>

</tr>