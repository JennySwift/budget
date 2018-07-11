<template>
    <div id="budget-toolbar">
        <button v-on:click="toggleNewBudget()" class="btn btn-info">New</button>

        <!--<div>-->
            <!--<button-->
                <!--v-on:mouseenter="respondToMouseEnterOnTotalsButton"-->
                <!--v-on:mouseleave="respondToMouseLeaveOnTotalsButton"-->
                <!--class="btn btn-default totals-btn"-->
            <!--&gt;-->
                <!--Totals-->
            <!--</button>-->
        <!--</div>-->
    </div>
</template>

<script>
    import TotalsRepository from '../../repositories/TotalsRepository'
    export default {
        data: function () {
            return {
                shared: store.state
            }
        },
        methods: {
            /**
             *
             */
            respondToMouseEnterOnTotalsButton: function () {
                TotalsRepository.respondToMouseEnterOnTotalsButton(this);
            },

            /**
             *
             */
            respondToMouseLeaveOnTotalsButton: function () {
                TotalsRepository.respondToMouseLeaveOnTotalsButton(this);
            },

            toggleNewBudget: function () {
                store.toggle('show.newBudget');
            }
        }
    }
</script>