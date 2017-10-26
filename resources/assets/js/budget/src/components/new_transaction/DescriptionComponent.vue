<template>
    <div id="new-transaction-description" class="autocomplete-container">

        <div class="help-row">
            <label>Enter a description (optional)</label>

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
                            <div>As you type a description, previous transactions with a matching description will show up.</div>
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
            v-if="!shared.me.preferences.autocompleteDescription"
            v-model="shared.newTransaction.description"
            v-on:keyup.13="insertTransaction()"
            class="form-control"
            placeholder="description"
            type='text'
        >

        <autocomplete
            v-if="shared.me.preferences.autocompleteDescription"
            autocomplete-id="new-transaction-description-autocomplete"
            input-id="new-transaction-description-input"
            prop="description"
            :function-on-enter="insertTransaction"
            url="/api/transactions"
            field-to-filter-by="description"
        >
            <template scope="props" slot="options">
                <transaction-autocomplete-results
                    :options="props.options"
                    :current-index="props.currentIndex"
                >
                </transaction-autocomplete-results>

            </template>
        </autocomplete>

    </div>
</template>

<script>
    import NewTransactionRepository from '../../repositories/NewTransactionRepository'
    import TransactionAutocompleteResults from './TransactionAutocompleteResults.vue'
    export default {
        data: function () {
            return {
                shared: store.state,
            };
        },
        components: {
            'transaction-autocomplete-results': TransactionAutocompleteResults
        },
        methods: {
            insertTransaction () {
                NewTransactionRepository.insertTransactionSetup();
            }
        }
    }
</script>