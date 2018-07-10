<template>
    <div class="type">
        <div class="btn-group">
            <button v-on:click="setType('income')" v-bind:style="{background: shared.me.preferences.colors.income}" class="btn">Credit</button>
            <button v-on:click="setType('expense')" v-bind:style="{background: shared.me.preferences.colors.expense}" class="btn">Debit</button>
            <button v-on:click="setType('transfer')" v-bind:style="{background: shared.me.preferences.colors.transfer}" class="btn">Transfer</button>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                shared: store.state,
            };
        },
        methods: {
            setType: function (type) {
                store.set(type, 'newTransaction.type');
            }
        }
    }
</script>