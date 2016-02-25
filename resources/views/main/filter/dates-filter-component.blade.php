<script id="dates-filter-template" type="x-template">

    <div filter-dropdowns-directive
         class="section">

        <h4 class="center">date</h4>

        <div class="content">

            <div
                    v-show="filterTab === 'show'"
                    v-if="filter.singleDate"
                    class="group"
            >

                <input
                        v-model="filter.singleDate.in"
                        v-on:keyup.13="filterDate()"
                        type="text"
                        placeholder="single date"
                        formatted-date>

            <span class="input-group-btn">
                <button
                        v-on:click="clearDateField('singleDate', 'in')"
                        class="clear-search-button">
                    clear
                </button>
            </span>

            </div>

            <div
                    v-show="filterTab === 'hide'"
                    v-if="filter.singleDate"
                    class="group"
            >

                <input
                        v-model="filter.singleDate.out"
                        v-on:keyup.13="filterDate()"
                        type="text"
                        placeholder="single date"
                        formatted-date>

            <span class="input-group-btn">
                <button
                        v-on:click="clearDateField('singleDate', 'out')"
                        class="clear-search-button">
                    clear
                </button>
            </span>

            </div>

            <div
                v-show="filterTab === 'show'"
                v-if="filter.fromDate"
                class="group"
            >

                <input
                        v-model="filter.fromDate.in"
                        v-on:keyup.13="filterDate()"
                        type="text"
                        placeholder="from a date"
                        formatted-date>

            <span class="input-group-btn">
                <button
                        v-on:click="clearDateField('fromDate', 'in')"
                        class="clear-search-button"
                        type="button">
                    clear
                </button>
            </span>

            </div>

            <div
                    v-show="filterTab === 'show'"
                    v-if="filter.toDate"
                    class="group"
            >

                <input
                        v-model="filter.toDate.in"
                        v-on:keyup.13="filterDate()"
                        type="text"
                        placeholder="to a date"
                        formatted-date>

            <span class="input-group-btn">
                <button
                        v-on:click="clearDateField('toDate', 'in')"
                        class="clear-search-button"
                        type="button">
                    clear
                </button>
            </span>

            </div>

        </div>

    </div>

</script>
