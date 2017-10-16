<template>
    <div id="filter">I am the filter!</div>
    <!--<div-->
        <!--v-cloak-->
        <!--v-show="shared.show.filter"-->
        <!--transition="filter"-->
        <!--id="filter"-->
        <!--class="margin-bottom"-->
    <!--&gt;-->

        <!--<ul class="nav nav-tabs">-->
            <!--<li-->
                <!--role="presentation"-->
                <!--v-on:click="filterTab = 'show'"-->
                <!--v-bind:class="{'active': filterTab === 'show'}"-->
                <!--class="show-tab"-->
            <!--&gt;-->
                <!--<a href="#">-->
                    <!--Show-->
                <!--</a>-->
            <!--</li>-->

            <!--<li-->
                <!--role="presentation"-->
                <!--v-on:click="filterTab = 'hide'"-->
                <!--v-bind:class="{'active': filterTab === 'hide'}"-->
                <!--class="hide-tab"-->
            <!--&gt;-->
                <!--<a href="#">-->
                    <!--Hide-->
                <!--</a>-->
            <!--</li>-->
        <!--</ul>-->

        <!--<saved-filters-->
            <!--:run-filter="runFilter"-->
            <!--:filter.sync="filter"-->
        <!--&gt;-->
        <!--</saved-filters>-->

        <!--<toolbar-for-filter-->
            <!--:filter="filter"-->
            <!--:filter-totals="filterTotals"-->
            <!--:run-filter="runFilter"-->
        <!--&gt;-->
        <!--</toolbar-for-filter>-->

        <!--<div>-->
            <!--<totals-for-filter-->
                <!--:show="show"-->
                <!--:filter="filter"-->
                <!--:filter-totals="filterTotals"-->
            <!--&gt;-->
            <!--</totals-for-filter>-->

            <!--<div>-->
                <!--<accounts-filter-->
                    <!--:filter="filter"-->
                    <!--:filter-tab="filterTab"-->
                    <!--:run-filter="runFilter"-->
                <!--&gt;-->
                <!--</accounts-filter>-->

                <!--<types-filter-->
                    <!--:filter="filter"-->
                    <!--:filter-tab="filterTab"-->
                    <!--:run-filter="runFilter"-->
                <!--&gt;-->
                <!--</types-filter>-->

                <!--<descriptions-filter-->
                    <!--:filter="filter"-->
                    <!--:filter-tab="filterTab"-->
                    <!--:run-filter="runFilter"-->
                    <!--:clear-filter-field="clearFilterField"-->
                <!--&gt;-->
                <!--</descriptions-filter>-->

                <!--<merchants-filter-->
                    <!--:filter="filter"-->
                    <!--:filter-tab="filterTab"-->
                    <!--:run-filter="runFilter"-->
                    <!--:clear-filter-field="clearFilterField"-->
                <!--&gt;-->
                <!--</merchants-filter>-->

                <!--<budgets-filter-->
                    <!--:filter="filter"-->
                    <!--:filter-tab="filterTab"-->
                    <!--:run-filter="runFilter"-->
                    <!--:budgets="budgets"-->
                <!--&gt;-->
                <!--</budgets-filter>-->

                <!--<dates-filter-->
                    <!--:filter="filter"-->
                    <!--:filter-tab="filterTab"-->
                    <!--:run-filter="runFilter"-->
                    <!--:clear-filter-field="clearFilterField"-->
                <!--&gt;-->
                <!--</dates-filter>-->

                <!--<totals-filter-->
                    <!--:filter="filter"-->
                    <!--:filter-tab="filterTab"-->
                    <!--:run-filter="runFilter"-->
                    <!--:clear-filter-field="clearFilterField"-->
                <!--&gt;-->
                <!--</totals-filter>-->

                <!--<reconciled-filter-->
                    <!--:filter="filter"-->
                    <!--:filter-tab="filterTab"-->
                    <!--:run-filter="runFilter"-->
                <!--&gt;-->
                <!--</reconciled-filter>-->

                <!--<invalid-allocation-filter-->
                    <!--:filter="filter"-->
                    <!--:filter-tab="filterTab"-->
                    <!--:run-filter="runFilter"-->
                <!--&gt;-->
                <!--</invalid-allocation-filter>-->

                <!--<num-budgets-filter-->
                    <!--:filter="filter"-->
                    <!--:filter-tab="filterTab"-->
                    <!--:run-filter="runFilter"-->
                <!--&gt;-->
                <!--</num-budgets-filter>-->

            <!--</div>-->
        <!--</div>-->

    <!--</div>-->
</template>

<script>
    import FilterRepository from '../../repositories/FilterRepository'
    export default {
        data: function () {
            return {
                shared: store.state,
                filterTab: 'show',
                filterRepository: FilterRepository.state,
            };
        },
        components: {},
        computed: {
            filter: function () {
                return filterRepository.filter;
            },
            filterTotals: function () {
                return filterRepository.filterTotals;
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
            }
        },
        props: [
            'tab'
        ],
        mounted: function () {
            this.runFilter();
        }
    }
</script>