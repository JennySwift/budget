<script type="text/v-template" id="filter-merchant-template">


    <div filter-dropdowns-directive
         class="section">

        <h4 class="center">merchant</h4>

        <div class="content">

            <div class="group" v-show="filterTab === 'show'">
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

            <div class="group" v-show="filterTab === 'hide'">
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

</script>