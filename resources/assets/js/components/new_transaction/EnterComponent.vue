<template>
    <div>
        <div class="help-row">
            <button v-on:mousedown="insertTransaction()" tabindex="-1" id="add-transaction" class="btn btn-success">Add transaction</button>

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
                            <div>You can also add a new transaction by pressing enter on your keyboard when the cursor is in one of the inputs (unless pressing enter is to select an item from one of the dropdown menus).</div>
                        </div>
                    </div>

                </div>

            </dropdown>
        </div>

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
            }

        }
    }
</script>