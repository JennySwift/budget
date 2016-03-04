<script id="filter-template" type="x-template">

    <div
        v-cloak
        v-show="showFilter"
        transition="filter"
        id="filter"
        class="margin-bottom"
    >

        <div class="btn-group">
            <button v-on:click="filterTab = 'show'" class="btn btn-success">Show</button>
            <button v-on:click="filterTab = 'hide'" class="btn btn-danger">Hide</button>
        </div>

        <div v-if="filterTab === 'show'">
            Show tab
        </div>
        <div v-if="filterTab === 'hide'">
            Hide tab
        </div>

        <div class="form-group">
            <label for="saved-filter">Saved Filters</label>

            <select
                v-model="savedFilter"
                v-on:change="chooseSavedFilter(savedFilter)"
                id="saved-filter"
                class="form-control"
            >
                <option
                    v-for="savedFilter in savedFilters"
                    v-bind:value="savedFilter"
                >
                    @{{ savedFilter.name }}
                </option>
            </select>
        </div>

        <div class="toolbar-filter">
            {{--<filter-toolbar-directive></filter-toolbar-directive>--}}
        </div>

        <div class="flex">
            <totals-for-filter
                    :show="show"
                    :filter="filter"
            >
            </totals-for-filter>

            <div>
                <accounts-filter
                        :filter="filter"
                        :filterTab="filterTab"
                        :runFilter="runFilter()"
                >
                </accounts-filter>

                <types-filter
                        :filter="filter"
                        :filterTab="filterTab"
                        :runFilter="runFilter()"
                >
                </types-filter>

                <descriptions-filter
                        :filter="filter"
                        :filterTab="filterTab"
                        :runFilter="runFilter()"
                >
                </descriptions-filter>

                <merchants-filter
                        :filter="filter"
                        :filterTab="filterTab"
                        :runFilter="runFilter()"
                >
                </merchants-filter>

                <budgets-filter
                        filter="filter"
                        filterTab="filterTab"
                        runFilter="runFilter()"
                        budgets="budgets"
                >
                </budgets-filter>

                <dates-filter
                        :filter="filter"
                        :filterTab="filterTab"
                        :runFilter="runFilter()"
                >
                </dates-filter>

                <totals-filter
                        :filter="filter"
                        :filterTab="filterTab"
                        :runFilter="runFilter()">
                        :clearFilterField="clearFilterField()">
                </totals-filter>

                @include('main.home.filter.reconciled')
                @include('main.home.filter.num-budgets')
            </div>
        </div>

    </div>

</script>

