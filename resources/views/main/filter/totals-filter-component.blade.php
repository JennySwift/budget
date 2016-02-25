<script id="totals-filter-template" type="x-template">

    <div filter-dropdowns-directive
         class="section">

        <h4 class="center">amount</h4>

        <div class="content">

            <span>Negative sign required for negative numbers</span>

            <div
                    v-show="filterTab === 'show'"
                    v-if="filter.total"
                    class="group">
                <input
                        v-model="filter.total.in"
                        v-on:keyup="filterTotal($event.keyCode)"
                        type="text"
                        placeholder="amount">

            <span class="input-group-btn">
                <button
                        v-on:click="clearFilterField('total', 'in')"
                        class="clear-search-button"
                        type="button">
                    clear
                </button>
            </span>
            </div>

            <div
                    v-show="filterTab === 'hide'"
                    v-if="filter.total"
                    class="group">
                <input
                        v-model="filter.total.out"
                        v-on:keyup="filterTotal($event.keyCode)"
                        type="text"
                        placeholder="amount">

            <span class="input-group-btn">
                <button
                        v-on:click="clearFilterField('total', 'out')"
                        class="clear-search-button"
                        type="button">
                    clear
                </button>
            </span>
            </div>

        </div>

    </div>

</script>
