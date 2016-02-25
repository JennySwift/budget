<h3 v-if="transactions.length === 0">No transactions to show.</h3>

<table v-if="transactions.length > 0" id="transactions" class="">

    @include('templates.home.transactions.table-header')

    <tbody
        v-repeat="transaction in transactions"
        v-bind:style="{color: colors[transaction.type]}"
        id="[[transaction.id]]"
        class="add_to_search_total results-transaction-tbody [[transaction.type]]">

        <tr class="results_inner_div">

            <td v-show="show.date">[[transaction.userDate]]</td>

            <td v-show="show.description" class="description">
                <div>
                    <div>[[transaction.description]]</div>
                </div>

                <div v-if="transaction.description" class="tooltip-container">
                    <div class="tooltip">[[transaction.description]]</div>
                </div>
            </td>

            <td v-show="show.merchant" class="merchant">
                <div>
                    <div>[[transaction.merchant]]</div>
                </div>

                <div v-if="transaction.merchant" class="tooltip-container">
                    <div class="tooltip">[[transaction.merchant]]</div>
                </div>
            </td>

            <td v-show="show.total">
                {{--<span class="badge badge-[[transaction.type]]">[[transaction.total]]</span>--}}
                [[transaction.total | number:2]]
            </td>

            <td v-show="show.account" class="max-width-md">[[transaction.account.name]]</td>

            <td v-show="show.duration">
                <span v-if="transaction.minutes">[[transaction.minutes | formatDurationFilter]]</span>
            </td>

            <td v-show="show.reconciled">
                <input v-model="transaction.reconciled" v-on:change="updateReconciliation(transaction)" type="checkbox">
            </td>

            <td v-show="show.dlt" v-on:click="deleteTransaction(transaction)" class="pointer">
                <i class="fa fa-times"></i>
            </td>

            <td>
                <button v-on:click="updateTransactionSetup(transaction)" class="fa fa-pencil-square-o"></button>
            </td>

            <td v-show="show.allocated">
                <button
                    v-if="transaction.multipleBudgets"
                    v-bind:class="{'allocated, btn-success': transaction.allocated, 'not-allocated, btn-danger': !transaction.allocated}"
                    v-on:click="showAllocationPopup(transaction)">
                    allocate
                </button>
            </td>

        </tr>

        @include('templates.home.transactions.tags')

    </tbody>
</table>