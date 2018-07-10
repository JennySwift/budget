<template>
    <div
        v-slide="showContent"
        class="section"
    >

        <h4 v-on:click="showContent = !showContent">Dates <dropdown-arrow :content-visible="showContent"></dropdown-arrow></h4>

        <div class="content">

            <!--Single date in-->
            <div
                v-show="filterTab === 'show'"
                v-if="shared.filter.singleDate"
                class="form-group"
            >
                <label for="filter-single-date-in">Filter in by date</label>

                <div
                    class="input-group"
                >
                    <filter-input-field id="filter-single-date-in" :model="shared.filter.singleDate.in" path="filter.singleDate.in"></filter-input-field>

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
                v-if="shared.filter.singleDate"
                class="form-group"
            >
                <label for="filter-single-date-out">Filter out by date</label>

                <div
                    class="input-group"
                >
                    <filter-input-field id="filter-single-date-out" :model="shared.filter.singleDate.out" path="filter.singleDate.out"></filter-input-field>

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
                v-if="shared.filter.fromDate"
                class="form-group"
            >
                <label for="filter-from-date-in">Filter in by date on or after...</label>

                <div
                    class="input-group"
                >
                    <filter-input-field id="filter-from-date-in" :model="shared.filter.fromDate.in" path="filter.fromDate.in"></filter-input-field>

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
                v-if="shared.filter.toDate"
                class="form-group"
            >
                <label for="filter-to-date-in">Filter in by date on or before...</label>

                <div
                    class="input-group"
                >
                    <filter-input-field id="filter-to-date-in" :model="shared.filter.toDate.in" path="filter.toDate.in"></filter-input-field>

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
                showContent: false,
                shared: store.state,
                store: store
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