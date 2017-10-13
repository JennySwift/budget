<template>
    <div
        v-slide="showContent"
        class="section"
    >

        <h4 v-on:click="showContent = !showContent" class="center">date</h4>

        <div class="content">

            <!--Single date in-->
            <div
                v-show="filterTab === 'show'"
                v-if="filter.singleDate"
                class="form-group"
            >
                <label for="filter-single-date-in">Filter in by date</label>

                <div
                    class="input-group"
                >
                    <input
                        v-model="filter.singleDate.in"
                        v-on:keyup.13="runFilter()"
                        type="text"
                        id="filter-single-date-in"
                        name="filter-single-date-in"
                        placeholder="date"
                        class="form-control"
                        formatted-date
                    >
                    <span class="input-group-btn">
                         <button
                             v-on:click="clearFilterField('singleDate', 'in')"
                             class="clear-search-button btn btn-default">
                             clear
                         </button>
                    </span>
                </div>

            </div>

            <!--Single date out-->
            <div
                v-show="filterTab === 'hide'"
                v-if="filter.singleDate"
                class="form-group"
            >
                <label for="filter-single-date-out">Filter out by date</label>

                <div
                    class="input-group"
                >
                    <input
                        v-model="filter.singleDate.out"
                        v-on:keyup.13="runFilter()"
                        type="text"
                        id="filter-single-date-out"
                        name="filter-single-date-out"
                        placeholder="date"
                        class="form-control"
                        formatted-date
                    >
                    <span class="input-group-btn">
                        <button
                            v-on:click="clearFilterField('singleDate', 'out')"
                            class="clear-search-button btn btn-default">
                            clear
                        </button>
                    </span>
                </div>

            </div>

            <!--From date in-->
            <div
                v-show="filterTab === 'show'"
                v-if="filter.fromDate"
                class="form-group"
            >
                <label for="filter-from-date-in">Filter in by date on or after...</label>

                <div
                    class="input-group"
                >
                    <input
                        v-model="filter.fromDate.in"
                        v-on:keyup.13="runFilter()"
                        type="text"
                        id="filter-from-date-in"
                        name="filter-from-date-in"
                        placeholder="date"
                        class="form-control"
                        formatted-date
                    >
                    <span class="input-group-btn">
                        <button
                            v-on:click="clearFilterField('fromDate', 'in')"
                            class="clear-search-button btn btn-default">
                            clear
                        </button>
                    </span>
                </div>

            </div>

            <!--To date in-->
            <div
                v-show="filterTab === 'show'"
                v-if="filter.toDate"
                class="form-group"
            >
                <label for="filter-to-date-in">Filter in by date on or before...</label>

                <div
                    class="input-group"
                >
                    <input
                        v-model="filter.toDate.in"
                        v-on:keyup.13="runFilter()"
                        type="text"
                        id="filter-to-date-in"
                        name="filter-to-date-in"
                        placeholder="date"
                        class="form-control"
                        formatted-date
                    >
                    <span class="input-group-btn">
                        <button
                            v-on:click="clearFilterField('toDate', 'in')"
                            class="clear-search-button btn btn-default">
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
                showContent: false
            };
        },
        components: {},
        methods: {

        },
        props: [
            'filter',
            'filterTab',
            'runFilter',
            'clearFilterField'
        ],
        mounted: function () {

        }
    }
</script>