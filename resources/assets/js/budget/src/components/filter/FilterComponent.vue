<template>
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

        <saved-filters
            :run-filter="runFilter"
            :filter.sync="filter"
        >
        </saved-filters>

        <toolbar-for-filter
            :filter="filter"
            :filter-totals="filterTotals"
            :run-filter="runFilter"
        >
        </toolbar-for-filter>

        <div>
            <totals-for-filter
                :show="show"
                :filter="filter"
                :filter-totals="filterTotals"
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
                    :run-filter="runFilter"
                    :clear-filter-field="clearFilterField"
                >
                </totals-filter>

                <reconciled-filter
                    :filter="filter"
                    :filter-tab="filterTab"
                    :run-filter="runFilter"
                >
                </reconciled-filter>

                <invalid-allocation-filter
                    :filter="filter"
                    :filter-tab="filterTab"
                    :run-filter="runFilter"
                >
                </invalid-allocation-filter>

                <num-budgets-filter
                    :filter="filter"
                    :filter-tab="filterTab"
                    :run-filter="runFilter"
                >
                </num-budgets-filter>

            </div>
        </div>

    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                filterTab: 'show',
                filterRepository: FilterRepository.state,
                showFilter: false,
            };
        },
        components: {},
        computed: {
            filter: function () {
                return this.filterRepository.filter;
            },
            filterTotals: function () {
                return this.filterRepository.filterTotals;
            }
        },
        methods: {

            /**
             *
             */
            runFilter: function () {
                FilterRepository.runFilter(this);
            },

            /**
             *
             * @param field
             * @param type - either 'in' or 'out'
             */
            clearFilterField: function (field, type) {
                FilterRepository.clearFilterField(this, field, type);
            },

            /**
             *
             */
            listen: function () {
                var that = this;

                $(document).on('toggle-filter', function (event) {
                    that.showFilter = !that.showFilter;
                });
            }
        },
        props: [
            'show',
            'budgets',
            'tab'
        ],
        mounted: function () {
            this.listen();
            this.runFilter();
        }
    }
</script>