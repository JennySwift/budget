<template>
    <table v-show="results.length > 0" class="table table-bordered">
        <tbody
            v-for="transaction in results"
            v-bind:style="{color: shared.me.preferences.colors[transaction.type]}"
            v-on:mousedown="chooseItem($index)"
            v-on:mouseover="hoverItem($index)"
            v-bind:class="{'selected': $index == currentIndex}"
            class="dropdown-item pointer">

        <tr>
            <th>description</th>
            <th>merchant</th>
            <th>total</th>
            <th>type</th>
            <th>account</th>
        </tr>

        <tr>
            <td class="description">{{ transaction.description }}</td>
            <td class="merchant">{{ transaction.merchant }}</td>
            <td class="total">{{ transaction.total }}</td>
            <td class="type">{{ transaction.type }}</td>

            <td v-if="transaction.account" class="account">{{ transaction.account.name }}</td>
            <td v-else class="account"></td>
        </tr>

        <tr>
            <td colspan="7">
                <li
                    v-for="budget in transaction.budgets"
                    v-bind:class="{'tag-with-fixed-budget': budget.type === 'fixed', 'tag-with-flex-budget': budget.type === 'flex', 'tag-without-budget': budget.type === 'unassigned'}"
                    class="label label-default">
                    {{ budget.name }}
                </li>
            </td>
            <!--<td colspan="1" class="budget-tag-info">{{ transaction.allocate }}</td>-->
        </tr>

        </tbody>
    </table>
</template>

<script>
    export default {
//        this.results = AutocompleteRepository.removeDuplicates(response);
        methods: {
            /**
             *
             */
            fillFields: function () {
                if (this.placeholder === 'description') {
                    this.typing = this.selectedItem.description;
                    this.newTransaction.merchant = this.selectedItem.merchant;
                }
                else if (this.placeholder === 'merchant') {
                    this.typing = this.selectedItem.merchant;
                    this.newTransaction.description = this.selectedItem.description;
                }

                // If the user has the clearFields setting on,
                // only fill in the total if they haven't entered a total yet
                if (shared.me.preferences.clearFields && this.newTransaction.total === '') {
                    this.newTransaction.total = this.selectedItem.total;
                }
                else if (!shared.me.preferences.clearFields) {
                    this.newTransaction.total = this.selectedItem.total;
                }

                this.newTransaction.type = this.selectedItem.type;

                //It didn't work setting the whole object so I'm setting the account id and name
                this.newTransaction.account.id = this.selectedItem.account.id;
                this.newTransaction.account.name = this.selectedItem.account.name;

                // if (this.selectedItem.fromAccount && this.selectedItem.toAccount) {
                //     this.newTransaction.fromAccount = this.selectedItem.fromAccount;
                //     this.newTransaction.toAccount = this.selectedItem.toAccount;
                // }

                this.newTransaction.budgets = this.selectedItem.budgets;
            },
        }
    }
</script>