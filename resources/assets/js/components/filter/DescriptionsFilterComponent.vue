<template>
    <div v-slide="showContent" class="section">

        <h4 v-on:click="showContent = !showContent">Description <dropdown-arrow :content-visible="showContent"></dropdown-arrow></h4>

        <div class="content">

            <div
                v-if="shared.filter.description"
                v-show="filterTab === 'show'"
                class="form-group"
            >
                <label for="filter-description-in">Filter description in</label>

                <filter-input-field field="description" type="in" id="filter-description-in" :model="shared.filter.description.in" path="filter.description.in"></filter-input-field>
            </div>

            <div
                v-if="shared.filter.description"
                v-show="filterTab === 'hide'"
                class="form-group"
            >
                <label for="filter-description-in">Filter description out</label>

                <filter-input-field field="description" type="out" id="filter-description-out" :model="shared.filter.description.out" path="filter.description.out"></filter-input-field>
            </div>

        </div>

    </div>
</template>

<script>
    import FilterRepository from '../../repositories/FilterRepository'
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