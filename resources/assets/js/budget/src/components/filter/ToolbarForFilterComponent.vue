<template>
    <div class="toolbar-filter">


        <select
            v-model="filter.numToFetch"
            v-on:change="changeNumToFetch()"
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

        {{--        <span class="badge">@{{ filter.displayFrom }} to @{{ filter.displayTo }} of @{{ filterTotals.numTransactions }}</span>--}}

        <div class="btn-group">

            <button
                v-on:click="prevResults()"
                :disabled="filter.displayFrom <= 1"
                type="button"
                id="prev-results-button"
                class="navigate-results-button btn btn-default">
                Prev
            </button>

            <button
                v-on:click="nextResults()"
                :disabled="filter.displayTo >= filterTotals.numTransactions"
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

            <new-saved-filter
                :filter="filter"
            >
            </new-saved-filter>

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
    export default {
        data: function () {
            return {
                filterRepository: FilterRepository.state
            };
        },
        components: {},
        computed: {
            filter: function () {
                return this.filterRepository.filter;
            }
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
                this.runFilter();
            },

            /**
             *
             */
            changeNumToFetch: function () {
                FilterRepository.updateRange(this.filter.numToFetch);
                this.runFilter();
            },

            /**
             * Todo: I might not need some of this code (not allowing offset to be less than 0)
             * since I disabled the button if that is the case
             */
            prevResults: function () {
                FilterRepository.prevResults(this);
            },

            /**
             *
             */
            nextResults: function () {
                FilterRepository.nextResults(this);
            },

            /**
             *
             */
            listen: function () {
                var that = this;
            }
        },
        props: [
            'filterTotals',
            'runFilter'
        ],
        mounted: function () {
            this.listen();
        }
    }
</script>