<script id="totals-filter-template" type="x-template">

    <div v-slide="showContent" class="section">

        <h4 v-on:click="showContent = !showContent" class="center">amount</h4>

        <div class="content">

            <div
                v-show="filterTab === 'show'"
                v-if="filter.total"
                class="form-group"
            >

                <label for="filter-total-in">Filter in by total (negative sign required for expenses)</label>

                <div
                    class="input-group"
                >
                    <input
                            v-model="filter.total.in"
                            v-on:keyup.13="filterTotal()"
                            type="text"
                            id="filter-total-in"
                            name="filter-total-in"
                            placeholder="total"
                            class="form-control"
                    >
                    <span class="input-group-btn">
                         <button
                                 v-on:click="clearFilterField('total', 'in')"
                                 class="clear-search-button btn btn-default"
                                 type="button">
                             clear
                         </button>
                    </span>
                </div>

            </div>

            <div
                v-show="filterTab === 'hide'"
                v-if="filter.total"
                class="form-group">

                <label for="filter-total-out">Filter out by total (negative sign required for expenses)</label>

                <div
                        class="input-group"
                >
                    <input
                            v-model="filter.total.out"
                            v-on:keyup.13="filterTotal()"
                            type="text"
                            id="filter-total-out"
                            name="filter-total-out"
                            placeholder="total"
                            class="form-control"
                    >
                    <span class="input-group-btn">
                         <button
                                 v-on:click="clearFilterField('total', 'out')"
                                 class="clear-search-button btn btn-default"
                                 type="button">
                             clear
                         </button>
                    </span>
                </div>

            </div>

        </div>

    </div>

</script>
