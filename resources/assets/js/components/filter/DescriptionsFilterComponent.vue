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

                <div class="input-group">
                    <input
                        v-model="shared.filter.description.in"
                        v-on:keyup.13="filterDescriptionOrMerchant()"
                        type="text"
                        id="filter-description-in"
                        name="filter-description-in"
                        placeholder="description"
                        class="form-control"
                    >

                    <span class="input-group-btn">
                        <button
                            v-on:click="clearFilterField('description', 'in')"
                            class="clear-search-button btn btn-default">
                            clear
                        </button>
                    </span>
                </div>

            </div>



            <div
                v-if="shared.filter.description"
                v-show="filterTab === 'hide'"
                class="form-group"
            >
                <label for="filter-description-in">Filter description out</label>

                <div class="input-group">
                    <input
                        v-model="shared.filter.description.out"
                        v-on:keyup.13="filterDescriptionOrMerchant()"
                        type="text"
                        id="filter-description-out"
                        name="filter-description-out"
                        placeholder="description"
                        class="form-control"
                    >

                    <span class="input-group-btn">
                        <button
                            v-on:click="clearFilterField('description', 'out')"
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

            /**
             *
             */
            filterDescriptionOrMerchant: function () {
                FilterRepository.resetOffset();
                store.runFilter();
            },

        },
        props: [
            'filterTab',
            'clearFilterField'
        ],
        mounted: function () {

        }
    }
</script>