<script type="text/v-template" id="filter-date-template">

    <div filter-dropdowns-directive
         class="section">

        <h4 class="center">date</h4>

        <div class="content">

            <div v-show="filterTab === 'show'" class="group">

                <input
                        v-model="filter.single_date.in"
                        v-on:keyup="filterDate($event.keyCode)"
                        type="text"
                        placeholder="single date"
                        formatted-date>

            <span class="input-group-btn">
                <button
                        v-on:click="clearDateField('single_date', 'in')"
                        class="clear-search-button">
                    clear
                </button>
            </span>

            </div>

            <div v-show="filterTab === 'hide'" class="group">

                <input
                        v-model="filter.single_date.out"
                        v-on:keyup="filterDate($event.keyCode)"
                        type="text"
                        placeholder="single date"
                        formatted-date>

            <span class="input-group-btn">
                <button
                        v-on:click="clearDateField('single_date', 'out')"
                        class="clear-search-button">
                    clear
                </button>
            </span>

            </div>

            <div v-show="filterTab === 'show'" class="group">

                <input
                        v-model="filter.from_date.in"
                        v-on:keyup="filterDate($event.keyCode)"
                        type="text"
                        placeholder="from a date"
                        formatted-date>

            <span class="input-group-btn">
                <button
                        v-on:click="clearDateField('from_date', 'in')"
                        class="clear-search-button"
                        type="button">
                    clear
                </button>
            </span>

            </div>

            <div v-show="filterTab === 'show'" class="group">

                <input
                        v-model="filter.to_date.in"
                        v-on:keyup="filterDate($event.keyCode)"
                        type="text"
                        placeholder="to a date"
                        formatted-date>

            <span class="input-group-btn">
                <button
                        v-on:click="clearDateField('to_date', 'in')"
                        class="clear-search-button"
                        type="button">
                    clear
                </button>
            </span>

            </div>

        </div>

    </div>

</script>
