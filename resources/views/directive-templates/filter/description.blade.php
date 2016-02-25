<script type="text/v-template" id="filter-description-template">

    <div filter-dropdowns-directive
         class="section">

        <h4 class="center">description</h4>

        <div class="content">

            <div class="group" v-show="filterTab === 'show'">
                <input
                        v-model="filter.description.in"
                        v-on:keyup="filterDescriptionOrMerchant($event.keyCode)"
                        type="text"
                        placeholder="description">

            <span class="input-group-btn">
                <button
                        v-on:click="clearFilterField('description', 'in')"
                        class="clear-search-button">
                    clear
                </button>
            </span>

            </div>

            <div class="group" v-show="filterTab === 'hide'">
                <input
                        v-model="filter.description.out"
                        v-on:keyup="filterDescriptionOrMerchant($event.keyCode)"
                        type="text"
                        placeholder="description">

            <span class="input-group-btn">
                <button
                        v-on:click="clearFilterField('description', 'out')"
                        class="clear-search-button">
                    clear
                </button>
            </span>
            </div>


        </div>

    </div>

</script>