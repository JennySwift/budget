<template>
    <div>
        <div v-cloak v-show=" shared.newTransaction.type !== 'transfer'">

            <div class="form-group">
                <label for="new-transaction-account">Account</label>

                <select
                    v-model="shared.newTransaction.account"
                    v-on:keyup.13="insertTransaction()"
                    id="new-transaction-account"
                    class="form-control"
                >
                    <option
                        v-for="account in shared.accounts"
                        v-bind:value="account"
                    >
                        {{ account.name }}
                    </option>
                </select>
            </div>

        </div>

        <div v-cloak v-show="shared.newTransaction.type === 'transfer'">

            <div class="form-group">
                <label for="new-transaction-from-account">Select the account your are transferring money from</label>

                <select
                    v-model="shared.newTransaction.fromAccount"
                    v-on:keyup.13="insertTransaction()"
                    id="new-transaction-from-account"
                    class="form-control"
                >
                    <option
                        v-for="account in shared.accounts"
                        v-bind:value="account"
                    >
                        {{ account.name }}
                    </option>
                </select>
            </div>

        </div>

        <div v-cloak v-show="shared.newTransaction.type === 'transfer'">

            <div class="form-group">
                <label for="new-transaction-to-account">Select the account you are transferring money to</label>

                <select
                    v-model="shared.newTransaction.toAccount"
                    v-on:keyup.13="insertTransaction()"
                    id="new-transaction-to-account"
                    class="form-control"
                >
                    <option
                        v-for="account in shared.accounts"
                        v-bind:value="account"
                    >
                        {{ account.name }}
                    </option>
                </select>
            </div>

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
                NewTransactionRepository.insertTransaction();
            }
        }
    }
</script>