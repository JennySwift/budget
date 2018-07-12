<template>
    <div id="filter-toolbar">


        <select
            v-model="shared.filter.numToFetch"
            v-on:change="changeNumToFetch()"
            id="filter-how-many"
            class="form-control"
        >
            <option value="2">2</option>
            <option value="4">4</option>
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="30">30</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </select>

        <!--        <span class="badge">{{ filter.displayFrom }} to {{ filter.displayTo }} of {{ filterTotals.numTransactions }}</span>-->

        <div id="filter-navigation-buttons" class="btn-group">

            <button
                v-on:click="prevResults()"
                :disabled="shared.filter.displayFrom <= 1"
                type="button"
                id="prev-results-button"
                class="navigate-results-button btn btn-default">
                Prev
            </button>

            <button
                v-on:click="nextResults()"
                :disabled="shared.filter.displayTo >= shared.filterTotals.numTransactions"
                type="button"
                id="next-results-button"
                class="navigate-results-button btn btn-default">
                Next
            </button>

            <button
                v-on:click="resetFilter()"
                id="reset-search"
                class="btn btn-default">
                Reset
            </button>

            <new-saved-filter></new-saved-filter>

            <button
                v-on:click="showMassTransactionUpdatePopup()"
                class="btn btn-default"
            >
                Edit transactions
            </button>

        </div>


    </div>

</template>

<script>
    import FilterRepository from '../../repositories/FilterRepository'
    import NewSavedFilterComponent from '../../components/filter/NewSavedFilterComponent.vue'
    export default {
        data: function () {
            return {
                shared: store.state
            };
        },
        components: {
            'new-saved-filter': NewSavedFilterComponent,
        },
        methods: {

            /**
             *
             */
            showMassTransactionUpdatePopup: function () {
                $.event.trigger('show-mass-transaction-update-popup');
            },

            /**
             *
             */
            resetFilter: function () {
                FilterRepository.resetFilter();
                store.runFilter();
            },

            /**
             *
             */
            changeNumToFetch: function () {
                FilterRepository.updateRange(store.state.filter.numToFetch);
                store.runFilter();
            },

            /**
             * Todo: I might not need some of this code (not allowing offset to be less than 0)
             * since I disabled the button if that is the case
             */
            prevResults: function () {
                FilterRepository.prevResults();
            },

            /**
             *
             */
            nextResults: function () {
                FilterRepository.nextResults();
            },
        },
        mounted: function () {

        }
    }
</script>