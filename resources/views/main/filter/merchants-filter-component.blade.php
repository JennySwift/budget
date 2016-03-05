<script id="merchants-filter-template" type="x-template" xmlns:v-on="http://www.w3.org/1999/xhtml">

<div>
    <div v-slide="showContent" class="section">

        <h4 v-on:click="showContent = !showContent" class="center">merchant</h4>

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

</script>