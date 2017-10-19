<template>
    <div class="autocomplete-container">

        <div class="help-row">
            <label>Enter a merchant (optional)</label>

            <dropdown
                inline-template
                animate-in-class="flipInX"
                animate-out-class="flipOutX"
                class="dropdown-directive"
            >

                <div>

                    <button
                        v-on:click="toggleDropdown()"
                        tabindex="-1"
                        class="btn btn-info btn-xs"
                    >
                        Help
                        <span class="caret"></span>
                    </button>

                    <div class="dropdown-content animated">
                        <div class="help">
                            <div>As you type a merchant, previous transactions with a matching merchant will show up.</div>
                            <div>To fill in the fields with those of a previous transaction, either:</div>
                            <ol>
                                <li>Use the up and down arrow keys to select a previous transaction, then press enter.</li>
                                <li>Click on one of the previous transactions.</li>
                            </ol>
                            <div>Alternatively, you can just keep typing if you don't want to use the autocomplete.</div>
                        </div>
                    </div>

                </div>

            </dropdown>
        </div>

        <input
            v-if="!shared.me.preferences.autocompleteMerchant"
            v-model="shared.newTransaction.merchant"
            v-on:keyup.13="insertTransaction()"
            class="form-control"
            placeholder="merchant"
            type='text'
        >

        <transaction-autocomplete
            v-if="shared.me.preferences.autocompleteMerchant"
            placeholder="merchant"
            id="new-transaction-merchant"
            :typing.sync="shared.newTransaction.merchant"
            :function-on-enter="insertTransaction"
        >
        </transaction-autocomplete>

    </div>
</template>

<script>
    import NewTransactionRepository from '../../repositories/NewTransactionRepository'
    export default {
        data: function () {
            return {
                shared: store.state,
            };
        },
        methods: {
            insertTransaction () {
                NewTransactionRepository.insertTransaction();
            }
        }
    }
</script>