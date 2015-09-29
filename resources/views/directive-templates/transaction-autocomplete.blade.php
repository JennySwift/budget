
<script type="text/ng-template" id="transaction-autocomplete-template">

    <input
        ng-model="typing"
        ng-blur="hideAndClear()"
        ng-keyup="filter($event.keyCode)"
        placeholder="[[placeholder]]"
        class="form-control"
        type='text'>

    <div ng-show="dropdown && !loading" id="[[placeholder]]-autocomplete" class="transactions-autocomplete">
        <table class="table table-bordered">
            <tbody
                ng-repeat="transaction in results"
                ng-style="{color: colors[transaction.type]}"
                class="pointer"
                ng-mousedown="chooseItem($index)"
                ng-mouseover="hoverItem($index)"
                ng-class="{'selected': $index == currentIndex}"
                class="dropdown-item">

                <tr>
                    <th>description</th>
                    <th>merchant</th>
                    <th>total</th>
                    <th>type</th>
                    <th>account</th>
                    <th>from</th>
                    <th>to</th>
                </tr>

                <tr>
                    <td class="description">[[transaction.description]]</td>
                    <td class="merchant">[[transaction.merchant]]</td>
                    <td class="total">[[transaction.total]]</td>
                    <td class="type">[[transaction.type]]</td>
                    <td data-id="[[transaction.account.name]]" class="account">[[transaction.account.name]]</td>
                    <td>[[transaction.from_account.name]]</td>
                    <td>[[transaction.to_account.name]]</td>
                </tr>

                <tr>
                    <td colspan="7">
                        <li
                            ng-repeat="budget in transaction.budgets"
                            ng-class="{'tag-with-fixed-budget': budget.type === 'fixed', 'tag-with-flex-budget': budget.type === 'flex', 'tag-without-budget': budget.type === 'unassigned'}"
                            class="label label-default">
                            [[budget.name]]
                        </li>
                    </td>
                    <td colspan="1" class="budget-tag-info">[[transaction.allocate]]</td>
                </tr>

            </tbody>
        </table>

    </div>

</script>