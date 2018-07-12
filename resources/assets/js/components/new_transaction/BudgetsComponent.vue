<template>
    <div v-if="shared.newTransaction.type !== 'transfer'">
        <div class="help-row">
            <label>Add tags to your transaction (optional)</label>

            <dropdown
                inline-template
                animate-in-class="flipInX"
                animate-out-class="flipOutX"
                class="dropdown-directive"
            >

                <div>

                    <button v-on:click="toggleDropdown()" tabindex="-1" class="btn btn-info btn-xs">
                        Help
                        <span class="caret"></span>
                    </button>

                    <div class="dropdown-content animated">
                        <div class="help">
                            <div>To add tags to your transaction, start typing the name of your tag in the field, then use the up or down arrow keys to select a tag, then press enter.</div>
                            <div>Repeat the process to enter more than one tag.</div>
                            <div>The tag must first be created on the <a href="/tags">tags page</a> in order for it to show up as an option here.</div>
                            <div>If you press enter with no tag selected, the transaction will be entered.</div>
                        </div>
                    </div>

                </div>

            </dropdown>
        </div>

        <autocomplete
            autocomplete-id="new-transaction-budgets-autocomplete"
            input-id="new-transaction-budgets-input"
            :unfiltered-options="shared.budgets"
            prop="name"
            multiple-selections="true"
            :chosen-options="shared.newTransaction.budgets"
            :function-on-enter="insertTransaction"
        >
        </autocomplete>

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
                NewTransactionRepository.insertTransactionSetup();
            },
            optionChosen: function (option, inputId) {
                switch(inputId) {
                    case 'new-transaction-budgets-input':
                        store.add(option, 'newTransaction.budgets');
                        break;
                }
            },
            chosenOptionRemoved: function (option, inputId) {
                switch(inputId) {
                    case 'new-transaction-budgets-input':
                        store.delete(option, 'newTransaction.budgets');
                        break;
                }
            },
        },
        created: function () {
            this.$bus.$on('autocomplete-option-chosen', this.optionChosen);
            this.$bus.$on('autocomplete-chosen-option-removed', this.chosenOptionRemoved);
        }
    }
</script>