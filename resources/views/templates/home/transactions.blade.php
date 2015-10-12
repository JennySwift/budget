<h3 ng-if="transactions.length === 0">No transactions to show.</h3>

<table ng-if="transactions.length > 0" id="transactions" class="">

    @include('templates.home.transactions.table-header')

    <tbody
        ng-repeat="transaction in transactions"
        ng-style="{color: colors[transaction.type]}"
        id="[[transaction.id]]"
        class="add_to_search_total results-transaction-tbody [[transaction.type]]">

        <tr class="results_inner_div">

            <td ng-show="show.date">[[transaction.userDate]]</td>

            <td ng-show="show.description" class="description">
                <div>
                    <div>[[transaction.description]]</div>
                </div>

                <div ng-if="transaction.description" class="tooltip-container">
                    <div class="tooltip">[[transaction.description]]</div>
                </div>
            </td>

            <td ng-show="show.merchant" class="merchant">
                <div>
                    <div>[[transaction.merchant]]</div>
                </div>

                <div ng-if="transaction.merchant" class="tooltip-container">
                    <div class="tooltip">[[transaction.merchant]]</div>
                </div>
            </td>

            <td ng-show="show.total">
                {{--<span class="badge badge-[[transaction.type]]">[[transaction.total]]</span>--}}
                [[transaction.total | number:2]]
            </td>

            <td ng-show="show.account" class="max-width-md">[[transaction.account.name]]</td>

            <td ng-show="show.duration">
                <span ng-if="transaction.minutes">[[transaction.minutes | formatDurationFilter]]</span>
            </td>

            <td ng-show="show.reconciled">
                <input ng-model="transaction.reconciled" ng-change="updateReconciliation(transaction)" type="checkbox">
            </td>

            <td ng-show="show.dlt" ng-click="deleteTransaction(transaction)" class="pointer">
                <i class="fa fa-times"></i>
            </td>

            <td>
                <button ng-click="updateTransactionSetup(transaction)" class="fa fa-pencil-square-o"></button>
            </td>

            <td ng-show="show.allocated">
                <button
                    ng-if="transaction.multipleBudgets"
                    ng-class="{'allocated, btn-success': transaction.allocated, 'not-allocated, btn-danger': !transaction.allocated}"
                    ng-click="showAllocationPopup(transaction)">
                    allocate
                </button>
            </td>

        </tr>

        @include('templates.home.transactions.tags')

    </tbody>
</table>