<template>
    <table>
        <thead>
            <tr>
            <th>description</th>
            <th>merchant</th>
            <th>total</th>
            <th>type</th>
            <th>account</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="description">{{props.option.description}}</td>
                <td class="merchant">{{props.option.merchant}}</td>
                <td class="total">{{ props.option.total }}</td>
                <td class="type">{{ props.option.type }}</td>

                <td v-if="props.option.account" class="account">{{ props.option.account.name }}</td>
                <td v-if="!props.option.account" class="account"></td>
            </tr>

            <tr>
                <td colspan="7">
                    <ul>
                        <li
                            v-for="budget in props.option.budgets"
                            v-bind:class="{'tag-with-fixed-budget': budget.type === 'fixed', 'tag-with-flex-budget': budget.type === 'flex', 'tag-without-budget': budget.type === 'unassigned'}"
                            class="label label-default">
                            {{ budget.name }}
                        </li>
                    </ul>
                </td>
            <!--<td colspan="1" class="budget-tag-info">{{ transaction.allocate }}</td>-->
            </tr>
        </tbody>

    </table>
    <!--<table v-show="options.length > 0" class="table table-bordered transaction-autocomplete-results">-->
        <!--<thead>-->
            <!--<tr>-->
                <!--<th>description</th>-->
                <!--<th>merchant</th>-->
                <!--<th>total</th>-->
                <!--<th>type</th>-->
                <!--<th>account</th>-->
            <!--</tr>-->
        <!--</thead>-->
        <!--<tbody-->
            <!--v-for="(transaction, index) in options"-->
            <!--v-show="options.length > 0"-->
            <!--v-on:mousedown="fillFields(transaction)"-->
            <!--class="dropdown-item pointer autocomplete-option"-->
            <!--v-bind:style="{color: shared.me.preferences.colors[transaction.type]}"-->
        <!--&gt;-->

            <!--<tr>-->
                <!--<td class="description">{{ transaction.description }}</td>-->
                <!--<td class="merchant">{{ transaction.merchant }}</td>-->
                <!--<td class="total">{{ transaction.total }}</td>-->
                <!--<td class="type">{{ transaction.type }}</td>-->

                <!--<td v-if="transaction.account" class="account">{{ transaction.account.name }}</td>-->
                <!--<td v-else class="account"></td>-->
            <!--</tr>-->

            <!--<tr>-->
                <!--<td colspan="7">-->
                    <!--<li-->
                        <!--v-for="budget in transaction.budgets"-->
                        <!--v-bind:class="{'tag-with-fixed-budget': budget.type === 'fixed', 'tag-with-flex-budget': budget.type === 'flex', 'tag-without-budget': budget.type === 'unassigned'}"-->
                        <!--class="label label-default">-->
                        <!--{{ budget.name }}-->
                    <!--</li>-->
                <!--</td>-->
                <!--&lt;!&ndash;<td colspan="1" class="budget-tag-info">{{ transaction.allocate }}</td>&ndash;&gt;-->
            <!--</tr>-->

        <!--</tbody>-->
    <!--</table>-->
</template>

<script>
    export default {
//        this.options = AutocompleteRepository.removeDuplicates(response);
        data: function () {
            return {
                shared: store.state
            }
        },
        methods: {

        },
        props: [
            'props',
            'options',
            'currentIndex'
        ]
    }
</script>

<style lang="scss" type="text/scss">
    @import '../../../sass/variables';
    .transaction-autocomplete-results {
        tbody:hover {
            background: $success;
        }
    }
</style>