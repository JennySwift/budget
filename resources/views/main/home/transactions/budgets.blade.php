<tr
    v-if="transaction.budgets"
    v-show="transactionPropertiesToShow.budgets && transaction.budgets.length > 0"
    class="tag-location-container">

    <td colspan="9">
        <li
            v-for="budget in transaction.budgets"
            v-bind:class="{
                'tag-with-fixed-budget': budget.type === 'fixed',
                'tag-with-flex-budget': budget.type === 'flex',
                'tag-without-budget': budget.type === 'unassigned'
            }"
            class="label label-default budget"
            {{--data-id="@{{ tag.id }}"--}}
            {{--data-allocated-percent="@{{ budget.allocated_percent }}"--}}
            {{--data-allocated-fixed="@{{ budget.allocated_fixed }}"--}}
            {{--data-allocated_fixed="@{{ budget.allocated_fixed }}"--}}>
            <span>@{{ budget.name }}</span>
            <span v-if="budget.pivot">@{{ budget.pivot.calculated_allocation }}</span>
            <span class="type">@{{ budget.type }}</span>
        </li>
    </td>

</tr>