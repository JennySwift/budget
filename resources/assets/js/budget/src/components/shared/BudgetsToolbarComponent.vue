<template>
    <div id="budget-toolbar">
        <button v-on:click="toggleNewBudget()" class="btn btn-info">New</button>

        <div>
            <button
                v-on:mouseenter="respondToMouseEnterOnTotalsButton"
                v-on:mouseleave="respondToMouseLeaveOnTotalsButton"
                class="btn btn-default totals-btn"
            >
                Totals
            </button>
        </div>
    </div>
</template>

<script>
    export default {

    }
</script>