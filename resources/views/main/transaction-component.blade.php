<script id="transaction-template" type="x-template">

    <tbody>

        <tr class="results_inner_div">
        
            <td v-show="showDate">@{{ transaction.userDate }}</td>
        
            <td v-show="showDescription" class="description">
                <div>
                    <div>@{{ transaction.description }}</div>
                </div>
        
                <div v-if="transaction.description" class="tooltip-container">
                    <div class="tooltip">@{{ transaction.description }}</div>
                </div>
            </td>
        
            <td v-show="showMerchant" class="merchant">
                <div>
                    <div>@{{ transaction.merchant }}</div>
                </div>
        
                <div v-if="transaction.merchant" class="tooltip-container">
                    <div class="tooltip">@{{ transaction.merchant }}</div>
                </div>
            </td>
        
            <td v-show="showTotal">
                {{--<span class="badge badge-@{{ transaction.type }}">@{{ transaction.total }}</span>--}}
                @{{ transaction.total | numberFilter 2 }}
            </td>
        
            <td v-show="showAccount" class="max-width-md">@{{ transaction.account.name }}</td>
        
            <td v-show="showDuration">
                <span v-if="transaction.minutes">@{{ transaction.minutes | formatDurationFilter }}</span>
            </td>
        
            <td v-show="showReconciled">
                <input v-model="transaction.reconciled" v-on:change="updateReconciliation(transaction)" type="checkbox">
            </td>
        
            <td v-show="showDelete" v-on:click="deleteTransaction(transaction)" class="pointer">
                <i class="fa fa-times"></i>
            </td>
        
            <td>
                <button v-on:click="updateTransactionSetup(transaction)" class="fa fa-pencil-square-o"></button>
            </td>
        
            <td v-show="showAllocated">
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
        
{{--        @include('main.home.transactions.tags')--}}

    </tbody>

</script>