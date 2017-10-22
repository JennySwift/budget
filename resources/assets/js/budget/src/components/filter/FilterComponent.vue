<template>
    <transition name="filter-wrapper">
        <div v-show="shared.show.filter" id="filter-wrapper">
            <transition
                name="filter"
                v-cloak
                class="margin-bottom"
            >
                <div v-show="shared.show.filter" id="filter">
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
                    >
                    </saved-filters>

                    <toolbar-for-filter
                        :filter-totals="filterTotals"
                        :run-filter="runFilter"
                    >
                    </toolbar-for-filter>

                    <div>
                        <totals-for-filter></totals-for-filter>

                        <div>
                            <accounts-filter
                                :filter-tab="filterTab"
                                :run-filter="runFilter"
                            >
                            </accounts-filter>

                            <types-filter
                                :filter-tab="filterTab"
                                :run-filter="runFilter"
                            >
                            </types-filter>

                            <descriptions-filter
                                :filter-tab="filterTab"
                                :run-filter="runFilter"
                                :clear-filter-field="clearFilterField"
                            >
                            </descriptions-filter>

                            <merchants-filter
                                :filter-tab="filterTab"
                                :run-filter="runFilter"
                                :clear-filter-field="clearFilterField"
                            >
                            </merchants-filter>

                            <budgets-filter
                                :filter-tab="filterTab"
                                :run-filter="runFilter"
                            >
                            </budgets-filter>

                            <dates-filter
                                :filter-tab="filterTab"
                                :run-filter="runFilter"
                                :clear-filter-field="clearFilterField"
                            >
                            </dates-filter>

                            <totals-filter
                                :filter-tab="filterTab"
                                :run-filter="runFilter"
                                :clear-filter-field="clearFilterField"
                            >
                            </totals-filter>

                            <reconciled-filter
                                :filter-tab="filterTab"
                                :run-filter="runFilter"
                            >
                            </reconciled-filter>

                            <invalid-allocation-filter
                                :filter-tab="filterTab"
                                :run-filter="runFilter"
                            >
                            </invalid-allocation-filter>

                            <num-budgets-filter
                                :filter-tab="filterTab"
                                :run-filter="runFilter"
                            >
                            </num-budgets-filter>

                        </div>
                    </div>
                </div>

            </transition>
        </div>

    </transition>
</template>

<script>
    import FilterRepository from '../../repositories/FilterRepository'
    import SavedFiltersComponent from '../../components/filter/SavedFiltersComponent.vue'
    import ToolbarForFilterComponent from '../../components/filter/ToolbarForFilterComponent.vue'
    import TotalsForFilterComponent from '../../components/filter/TotalsForFilterComponent.vue'
    import AccountsFilterComponent from '../../components/filter/AccountsFilterComponent.vue'
    import TypesFilterComponent from '../../components/filter/TypesFilterComponent.vue'
    import DescriptionsFilterComponent from '../../components/filter/DescriptionsFilterComponent.vue'
    import MerchantsFilterComponent from '../../components/filter/MerchantsFilterComponent.vue'
    import BudgetsFilterComponent from '../../components/filter/BudgetsFilterComponent.vue'
    import DatesFilterComponent from '../../components/filter/DatesFilterComponent.vue'
    import TotalsFilterComponent from '../../components/filter/TotalsFilterComponent.vue'
    import ReconciledFilterComponent from '../../components/filter/ReconciledFilterComponent.vue'
    import InvalidAllocationFilterComponent from '../../components/filter/InvalidAllocationFilterComponent.vue'
    import NumBudgetsFilterComponent from '../../components/filter/NumBudgetsFilterComponent.vue'
    export default {
        data: function () {
            return {
                shared: store.state,
                filterTab: 'show',
                filterRepository: FilterRepository.state,
            };
        },
        components: {
            'saved-filters': SavedFiltersComponent,
            'toolbar-for-filter': ToolbarForFilterComponent,
            'totals-for-filter': TotalsForFilterComponent,
            'accounts-filter': AccountsFilterComponent,
            'types-filter': TypesFilterComponent,
            'descriptions-filter': DescriptionsFilterComponent,
            'merchants-filter': MerchantsFilterComponent,
            'budgets-filter': BudgetsFilterComponent,
            'dates-filter': DatesFilterComponent,
            'totals-filter': TotalsFilterComponent,
            'reconciled-filter': ReconciledFilterComponent,
            'invalid-allocation-filter': InvalidAllocationFilterComponent,
            'num-budgets-filter': NumBudgetsFilterComponent,
        },
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
            optionChosen: function (option, inputId) {
                if (inputId === 'filter-budgets-in-and-input') {
                    store.add(option, 'filter.budgets.in.and');
                    this.runFilter();
                }
            },
            chosenOptionRemoved: function (option, inputId) {
                if (inputId === 'filter-budgets-in-and-input') {
                    store.delete(option, 'filter.budgets.in.and');
                    this.runFilter();
                }
            },
        },
        props: [
            'tab'
        ],
        mounted: function () {
            var that = this;
            //If I don't do this timeout, app.__vue__ is undefined when I need it
            setTimeout(function () {
                that.runFilter();
            }, 100);
//            this.runFilter();
        },
        created: function () {
            this.$bus.$on('autocomplete-option-chosen', this.optionChosen);
            this.$bus.$on('autocomplete-chosen-option-removed', this.chosenOptionRemoved);
        }
    }
</script>