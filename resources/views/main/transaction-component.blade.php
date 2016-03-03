<script id="transaction-template" type="x-template">

    <tbody>

        <tr class="results_inner_div">
        
            <td
                v-show="showDate"
                v-on:click="showEditTransactionPopup(transaction)"
                class="pointer"
            >
                @{{ transaction.userDate }}
            </td>
        
            <td
                v-show="showDescription"
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
                v-show="showMerchant"
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
                v-show="showTotal"
                v-on:click="showEditTransactionPopup(transaction)"
                class="pointer"
            >
                {{--<span class="badge badge-@{{ transaction.type }}">@{{ transaction.total }}</span>--}}
                @{{ transaction.total | numberFilter 2 }}
            </td>
        
            <td
                v-show="showAccount"
                v-on:click="showEditTransactionPopup(transaction)"
                class="max-width-md pointer"
            >
                @{{ transaction.account.name }}
            </td>
        
            <td
                v-show="showDuration"
                v-on:click="showEditTransactionPopup(transaction)"
                class="pointer"
            >
                <span v-if="transaction.minutes">@{{ transaction.minutes | formatDurationFilter }}</span>
            </td>
        
            <td v-show="showReconciled"
            >
                <input v-model="transaction.reconciled" v-on:change="updateTransaction()" type="checkbox">
            </td>
        
            <tdv-show="showAllocated">
                <button
                        v-if="transaction.multipleBudgets"
                        v-bind:class="{
                            'allocated': transaction.allocated,
                            'btn-success': transaction.allocated,
                            'not-allocated': !transaction.allocated,
                            'btn-danger': !transaction.allocated,
                        }"
                        v-on:click="showAllocationPopup(transaction)">
                    allocate
                </button>
            </td>
        
        </tr>
        
        @include('main.home.transactions.budgets')

    </tbody>

</script>