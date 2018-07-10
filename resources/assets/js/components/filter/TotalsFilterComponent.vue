<template>
    <div v-slide="showContent" class="section">

        <h4 v-on:click="showContent = !showContent">Total <dropdown-arrow :content-visible="showContent"></dropdown-arrow></h4>

        <div class="content">

            <div
                v-show="filterTab === 'show'"
                v-if="shared.filter.total"
                class="form-group"
            >

                <label for="filter-total-in">Filter in by total (negative sign required for expenses)</label>

                <div
                    class="input-group"
                >
                    <input
                        v-model="shared.filter.total.in"
                        v-on:keyup.13="store.runFilter()"
                        type="text"
                        id="filter-total-in"
                        name="filter-total-in"
                        placeholder="total"
                        class="form-control"
                    >
                    <span class="input-group-btn">
                         <button
                             v-on:click="clearFilterField('total', 'in')"
                             class="clear-search-button btn btn-default"
                             type="button">
                             clear
                         </button>
                    </span>
                </div>

            </div>

            <div
                v-show="filterTab === 'hide'"
                v-if="shared.filter.total"
                class="form-group">

                <label for="filter-total-out">Filter out by total (negative sign required for expenses)</label>

                <div
                    class="input-group"
                >
                    <input
                        v-model="shared.filter.total.out"
                        v-on:keyup.13="store.runFilter()"
                        type="text"
                        id="filter-total-out"
                        name="filter-total-out"
                        placeholder="total"
                        class="form-control"
                    >
                    <span class="input-group-btn">
                         <button
                             v-on:click="clearFilterField('total', 'out')"
                             class="clear-search-button btn btn-default"
                             type="button">
                             clear
                         </button>
                    </span>
                </div>

            </div>

        </div>

    </div>

</template>

<script>
    export default {
        data: function () {
            return {
                showContent: false,
                shared: store.state
            };
        },
        components: {},
        methods: {

        },
        props: [
            'filterTab',
            'clearFilterField'
        ],
        mounted: function () {

        }
    }
</script>