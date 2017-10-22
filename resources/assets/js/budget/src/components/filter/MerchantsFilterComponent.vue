<template>
    <div>
        <div v-slide="showContent" class="section">

            <h4 v-on:click="showContent = !showContent">Merchant <dropdown-arrow :content-visible="showContent"></dropdown-arrow></h4>

            <div class="content">

                <div
                    v-show="filterTab === 'show'"
                    v-if="filter.merchant"
                    class="form-group"
                >

                    <label for="filter-merchant-in">Filter merchant in</label>

                    <div
                        class="input-group"
                    >
                        <input
                            v-model="filter.merchant.in"
                            v-on:keyup.13="filterDescriptionOrMerchant()"
                            type="text"
                            id="filter-merchant-in"
                            name="filter-merchant-in"
                            placeholder="merchant"
                            class="form-control"
                        >
                        <span class="input-group-btn">
                            <button
                                v-on:click="clearFilterField('merchant', 'in')"
                                class="clear-search-button btn btn-default">
                                clear
                            </button>
                        </span>
                    </div>

                </div>

                <div
                    v-show="filterTab === 'hide'"
                    v-if="filter.merchant"
                    class="form-group"
                >

                    <label for="filter-merchant-in">Filter merchant out</label>

                    <div
                        class="input-group"
                    >
                        <input
                            v-model="filter.merchant.out"
                            v-on:keyup.13="filterDescriptionOrMerchant()"
                            type="text"
                            id="filter-merchant-out"
                            name="filter-merchant-out"
                            placeholder="merchant"
                            class="form-control"
                        >
                        <span class="input-group-btn">
                            <button
                                v-on:click="clearFilterField('merchant', 'out')"
                                class="clear-search-button btn btn-default">
                                clear
                            </button>
                        </span>
                    </div>

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
                showContent: false
            };
        },
        components: {},
        methods: {

            /**
             *
             */
            filterDescriptionOrMerchant: function () {
                FilterRepository.resetOffset();
                this.runFilter();
            },
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