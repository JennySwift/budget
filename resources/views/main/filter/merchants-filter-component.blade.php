<script id="merchants-filter-template" type="x-template">

<div>
    <div v-slide="showContent" class="section">

        <h4 v-on:click="showContent = !showContent" class="center">merchant</h4>

        <div class="content">

            <div
                    v-show="filterTab === 'show'"
                    v-if="filter.merchant"
                    class="group"
            >
                <input
                        v-model="filter.merchant.in"
                        v-on:keyup="filterDescriptionOrMerchant($event.keyCode)"
                        type="text"
                        placeholder="merchant">

            <span class="input-group-btn">
                <button
                        v-on:click="clearFilterField('merchant', 'in')"
                        class="clear-search-button">
                    clear
                </button>
            </span>

            </div>

            <div
                    v-show="filterTab === 'hide'"
                    v-if="filter.merchant"
                    class="group"
            >
                <input
                        v-model="filter.merchant.out"
                        v-on:keyup="filterDescriptionOrMerchant($event.keyCode)"
                        type="text"
                        placeholder="merchant">

            <span class="input-group-btn">
                <button
                        v-on:click="clearFilterField('merchant', 'out')"
                        class="clear-search-button">
                    clear
                </button>
            </span>
            </div>

        </div>

    </div>
</div>

</script>