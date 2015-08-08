
<input
    ng-model="typing"
    ng-blur="hideAndClear()"
    ng-keyup="filter($event.keyCode)"
    placeholder="[[placeholder]]"
    class="form-control"
    type='text'>

<div ng-show="dropdown" id="[[placeholder]]-autocomplete" class="transactions-autocomplete">

    <table class="table table-bordered">
        <tbody ng-repeat="transaction in results"
               ng-style="{color: colors[transaction.type]}"
               class="pointer"
               ng-mousedown="chooseItem($index)"
               ng-mouseover="hoverItem($index)"
               ng-class="{'selected': $index == currentIndex}"
               class="dropdown-item">
        <!--        <div ng-bind-html="result.html"></div>-->

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
                    <li ng-repeat="tag in transaction.tags" ng-class="{'tag-with-budget': tag.budget !== null || tag.percent !== null, 'tag-without-budget': tag.budget === null || tag.percent === null}" class="label label-default" data-id="[[tag.id]]" data-allocated-percent="[[tag.allocated_percent]]" data-allocated-fixed="[[tag.allocated_fixed]]" data-amount="[[tag.amount]]">[[tag.name]]</li>
                </td>
                <td colspan="1" class="budget-tag-info">[[transaction.allocate]]</td>
            </tr>

        </tbody>
    </table>

</div>