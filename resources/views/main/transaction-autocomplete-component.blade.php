
<script id="transaction-autocomplete-template" type="x-template">

<div>
    
    <input
            v-model="typing"
            v-on:blur="hideAndClear()"
            v-on:focus="focus()"
            v-on:keyup="respondToKeyup($event.keyCode)"
            placeholder="@{{ placeholder }}"
            id="@{{ id }}"
            class="form-control"
            type='text'>

    <div v-show="showDropdown && !loading" id="@{{ placeholder }}-autocomplete" class="transactions-autocomplete">

        <div v-show="results.length < 1" class="no-results">No results</div>

        <table v-show="results.length > 0" class="table table-bordered">
            <tbody
                    v-for="transaction in results"
                    v-bind:style="{color: me.preferences.colors[transaction.type]}"
                    class="pointer"
                    v-on:mousedown="chooseItem($index)"
                    v-on:mouseover="hoverItem($index)"
                    v-bind:class="{'selected': $index == currentIndex}"
                    class="dropdown-item">

            <tr>
                <th>description</th>
                <th>merchant</th>
                <th>total</th>
                <th>type</th>
                <th>account</th>
            </tr>

            <tr>
                <td class="description">@{{ transaction.description }}</td>
                <td class="merchant">@{{ transaction.merchant }}</td>
                <td class="total">@{{ transaction.total }}</td>
                <td class="type">@{{ transaction.type }}</td>

                <td v-if="transaction.account" class="account">@{{ transaction.account.name }}</td>
                <td v-else class="account"></td>
            </tr>

            <tr>
                <td colspan="7">
                    <li
                            v-for="budget in transaction.budgets"
                            v-bind:class="{'tag-with-fixed-budget': budget.type === 'fixed', 'tag-with-flex-budget': budget.type === 'flex', 'tag-without-budget': budget.type === 'unassigned'}"
                            class="label label-default">
                        @{{ budget.name }}
                    </li>
                </td>
                {{--<td colspan="1" class="budget-tag-info">@{{ transaction.allocate }}</td>--}}
            </tr>

            </tbody>
        </table>

    </div>
</div>

</script>