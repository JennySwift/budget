<script id="transaction-template" type="x-template">

    <tbody>

        <tr class="results_inner_div">

            <td
                v-show="transactionPropertiesToShow.date"
                v-on:click="showEditTransactionPopup(transaction)"
                class="pointer"
            >
                @{{ transaction.userDate }}
            </td>

            <td
                v-show="transactionPropertiesToShow.description"
                v-on:click="showEditTransactionPopup(transaction)"
                class="description pointer"
            >
                <div>
                    <div>@{{ transaction.description }}</div>
                </div>

                <div v-if="transaction.description" class="tooltip-container">
                    <div class="tooltip">@{{ transaction.description }}</div>
                </div>
            </td>

            <td
                v-show="transactionPropertiesToShow.merchant"
                v-on:click="showEditTransactionPopup(transaction)"
                class="merchant pointer"
            >
                <div>
                    <div>@{{ transaction.merchant }}</div>
                </div>

                <div v-if="transaction.merchant" class="tooltip-container">
                    <div class="tooltip">@{{ transaction.merchant }}</div>
                </div>
            </td>

            <td
                v-show="transactionPropertiesToShow.total"
                v-on:click="showEditTransactionPopup(transaction)"
                class="pointer"
            >
                {{--<span class="badge badge-@{{ transaction.type }}">@{{ transaction.total }}</span>--}}
                @{{ transaction.total | numberFilter 2 }}
            </td>

            <td
                v-show="transactionPropertiesToShow.account"
                v-on:click="showEditTransactionPopup(transaction)"
                class="max-width-md pointer"
            >
                @{{ transaction.account.name }}
            </td>

            <td
                v-show="transactionPropertiesToShow.duration"
                v-on:click="showEditTransactionPopup(transaction)"
                class="pointer"
            >
                <span v-if="transaction.minutes">@{{ transaction.minutes | formatDurationFilter }}</span>
            </td>

            <td v-show="transactionPropertiesToShow.reconciled"
            >
                <input v-model="transaction.reconciled" v-on:change="updateTransaction()" type="checkbox">
            </td>

            <td v-show="transactionPropertiesToShow.allocated">
                <button
                        v-if="transaction.multipleBudgets"
                        v-bind:class="{
                            'allocated': transaction.allocated,
                            'btn-success': transaction.allocated,
                            'not-allocated': !transaction.allocated,
                            'btn-danger': !transaction.allocated,
                        }"
                        class="btn btn-sm"
                        v-on:click="showAllocationPopup(transaction)">
                    allocate
                </button>
            </td>

        </tr>

        @include('main.home.transactions.budgets')

    </tbody>

</script>