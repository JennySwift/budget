<script id="filter-template" type="x-template">

    <div
        v-cloak
        v-show="showFilter"
        transition="filter"
        id="filter"
        class="margin-bottom"
    >

        <ul class="nav nav-tabs">
            <li
                role="presentation"
                v-on:click="filterTab = 'show'"
                v-bind:class="{'active': filterTab === 'show'}"
                class="show-tab"
            >
                <a href="#">
                    Show
                </a>
            </li>

            <li
                role="presentation"
                v-on:click="filterTab = 'hide'"
                v-bind:class="{'active': filterTab === 'hide'}"
                class="hide-tab"
            >
                <a href="#">
                    Hide
                </a>
            </li>
        </ul>

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

        <div>
            <totals-for-filter
                    :show="show"
                    :filter="filter"
            >
            </totals-for-filter>

            <div>
                <accounts-filter
                        :filter="filter"
                        :filter-tab="filterTab"
                        :run-filter="runFilter"
                >
                </accounts-filter>

                <types-filter
                        :filter="filter"
                        :filter-tab="filterTab"
                        :run-filter="runFilter"
                >
                </types-filter>

                <descriptions-filter
                        :filter="filter"
                        :filter-tab="filterTab"
                        :run-filter="runFilter"
                        :clear-filter-field="clearFilterField"
                >
                </descriptions-filter>

                <merchants-filter
                        :filter="filter"
                        :filter-tab="filterTab"
                        :run-filter="runFilter"
                        :clear-filter-field="clearFilterField"
                >
                </merchants-filter>

                <budgets-filter
                        :filter="filter"
                        :filter-tab="filterTab"
                        :run-filter="runFilter"
                        :budgets="budgets"
                >
                </budgets-filter>

                <dates-filter
                        :filter="filter"
                        :filter-tab="filterTab"
                        :run-filter="runFilter"
                        :clear-filter-field="clearFilterField"
                >
                </dates-filter>

                <totals-filter
                        :filter="filter"
                        :filter-tab="filterTab"
                        :run-filter="runFilter">
                        :clear-filter-field="clearFilterField"
                >
                </totals-filter>

                <reconciled-filter
                        :filter="filter"
                        :filter-tab="filterTab"
                        :run-filter="runFilter"
                >
                </reconciled-filter>

                <num-budgets-filter
                        :filter="filter"
                        :filter-tab="filterTab"
                        :run-filter="runFilter"
                >
                </num-budgets-filter>

            </div>
        </div>

    </div>

</script>

