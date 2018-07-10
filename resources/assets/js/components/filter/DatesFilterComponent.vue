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

                <filter-input-field field="singleDate" type="in" id="filter-single-date-in" :model="shared.filter.singleDate.in" path="filter.singleDate.in"></filter-input-field>
            </div>

            <!--Single date out-->
            <div
                v-show="filterTab === 'hide'"
                v-if="shared.filter.singleDate"
                class="form-group"
            >
                <label for="filter-single-date-out">Filter out by date</label>

                <filter-input-field field="singleDate" type="out" id="filter-single-date-out" :model="shared.filter.singleDate.out" path="filter.singleDate.out"></filter-input-field>
            </div>

            <!--From date in-->
            <div
                v-show="filterTab === 'show'"
                v-if="shared.filter.fromDate"
                class="form-group"
            >
                <label for="filter-from-date-in">Filter in by date on or after...</label>

                <filter-input-field field="fromDate" type="in" id="filter-from-date-in" :model="shared.filter.fromDate.in" path="filter.fromDate.in"></filter-input-field>
            </div>

            <!--To date in-->
            <div
                v-show="filterTab === 'show'"
                v-if="shared.filter.toDate"
                class="form-group"
            >
                <label for="filter-to-date-in">Filter in by date on or before...</label>

                <filter-input-field field="toDate" type="in" id="filter-to-date-in" :model="shared.filter.toDate.in" path="filter.toDate.in"></filter-input-field>
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
        ],
        mounted: function () {

        }
    }
</script>