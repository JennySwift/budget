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

                    <saved-filters></saved-filters>

                    <toolbar-for-filter></toolbar-for-filter>

                    <div>
                        <totals-for-filter></totals-for-filter>

                        <div>
                            <accounts-filter :filter-tab="filterTab">
                            </accounts-filter>

                            <types-filter
                                :filter-tab="filterTab">
                            </types-filter>

                            <descriptions-filter
                                :filter-tab="filterTab"
                                label="Description"
                            >
                                <template slot="filterIn">
                                    <label for="filter-description-in">Filter description in</label>
                                    <filter-input-field field="description" type="in" id="filter-description-in" :model="shared.filter.description.in" path="filter.description.in"></filter-input-field>
                                </template>
                                <template slot="filterOut">
                                    <label for="filter-description-in">Filter description out</label>
                                    <filter-input-field field="description" type="out" id="filter-description-out" :model="shared.filter.description.out" path="filter.description.out"></filter-input-field>
                                </template>
                            </descriptions-filter>

                            <descriptions-filter
                                :filter-tab="filterTab"
                                label="Merchant"
                            >
                                <template slot="filterIn">
                                    <label for="filter-merchant-in">Filter merchant in</label>
                                    <filter-input-field field="merchant" type="in" id="filter-merchant-in" :model="shared.filter.merchant.in" path="filter.merchant.in"></filter-input-field>
                                </template>
                                <template slot="filterOut">
                                    <label for="filter-merchant-in">Filter merchant out</label>
                                    <filter-input-field field="merchant" type="out" id="filter-merchant-out" :model="shared.filter.merchant.out" path="filter.merchant.out"></filter-input-field>
                                </template>
                            </descriptions-filter>

                            <!--<merchants-filter-->
                                <!--:filter-tab="filterTab"-->
                            <!--&gt;-->
                            <!--</merchants-filter>-->

                            <budgets-filter
                                :filter-tab="filterTab"
                            >
                            </budgets-filter>

                            <dates-filter
                                :filter-tab="filterTab"
                            >
                            </dates-filter>

                            <totals-filter
                                :filter-tab="filterTab"
                            >
                            </totals-filter>

                            <reconciled-filter
                                :filter-tab="filterTab"
                            >
                            </reconciled-filter>

                            <invalid-allocation-filter
                                :filter-tab="filterTab"
                            >
                            </invalid-allocation-filter>

                            <num-budgets-filter
                                :filter-tab="filterTab"
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
    // import MerchantsFilterComponent from '../../components/filter/MerchantsFilterComponent.vue'
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
            // 'merchants-filter': MerchantsFilterComponent,
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
            }
        },
        methods: {

            /**
             *
             */
            runFilter: function () {
                FilterRepository.runFilter();
            },

            optionChosen: function (option, inputId) {
                switch(inputId) {
                    case 'filter-budgets-in-and-input':
                        store.add(option, 'filter.budgets.in.and');
                        this.runFilter();
                        break;
                    case 'filter-budgets-in-or-input':
                        store.add(option, 'filter.budgets.in.or');
                        this.runFilter();
                        break;
                    case 'filter-budgets-out-input':
                        store.add(option, 'filter.budgets.out');
                        this.runFilter();
                        break;
                }
            },
            chosenOptionRemoved: function (option, inputId) {
                switch(inputId) {
                    case 'filter-budgets-in-and-input':
                        store.delete(option, 'filter.budgets.in.and');
                        this.runFilter();
                        break;
                    case 'filter-budgets-in-or-input':
                        store.delete(option, 'filter.budgets.in.or');
                        this.runFilter();
                        break;
                    case 'filter-budgets-out-input':
                        store.delete(option, 'filter.budgets.out');
                        this.runFilter();
                        break;
                }
            },
        },
        props: [
            'tab'
        ],
        created: function () {
            this.$bus.$on('autocomplete-option-chosen', this.optionChosen);
            this.$bus.$on('autocomplete-chosen-option-removed', this.chosenOptionRemoved);
        }
    }
</script>

<style lang="scss" type="text/scss">
    @import '../../../sass/variables';
    $filterWidth: 392px;

    #filter-totals {
        .list-group-item {
            padding: 5px 15px;
        }
    }
    #filter-wrapper {
        width: $filterWidth;
        //There are two transition effects for the filter.
        //One on the filter to slide in and out.
        //And another on the filter wrapper to transition the width, so the transactions can smoothly change in width
        &.filter-wrapper-enter-active {
            transition: all .5s ease-out;
        }
        &.filter-wrapper-leave-active {
            transition: all .5s ease-in;
        }
        &.filter-wrapper-enter, &.filter-wrapper-leave-to {
            width: 0;
        }

        #filter {
            //animation
            animation: slideInRight .5s;
            &.filter-leave-active {
                animation: slideOutRight 2s;
            }

            #saved-filters-autocomplete {
                margin-bottom: 10px;
            }

            #filter-toolbar {
                margin-bottom: 12px;

                #filter-how-many {
                    width: 100%;
                    margin-bottom: 10px;
                }

                #filter-navigation-buttons {
                    width: 100%;
                    display: flex;
                    > * {
                        flex-grow: 1;
                    }
                }
            }



            //position: fixed;
            //top: 49px;
            //right: 0px;
            //height: 660px;
            //overflow: scroll;
            width: $filterWidth;
            //padding: 8px;
            z-index: 9;
            //box-shadow: -4px 4px 6px #777;
            background: white;

            .flex {
                display: flex;
            }

            .nav-tabs {
                display: flex;
                margin-bottom: 10px;
                > li {
                    flex-grow: 1;
                    text-align: center;
                }
                a:focus {
                    outline: none;
                }
                .show-tab.active {
                    a {
                        background: $success;
                        color: white;
                    }

                }
                .hide-tab.active {
                    a {
                        background: $danger;
                        color: white;
                    }
                }
            }
            .section {
                h4 {
                    margin: 0;
                    padding: 10px 0;
                    //background: black;
                    //color: #CFCFCF;
                    cursor: pointer;
                    text-align: left;
                }
                .content {
                    display: none;
                    padding-top: 15px;
                    .form-group {
                        margin-bottom: 0;
                        padding-bottom: 15px;
                    }
                    label {
                        &.disabled {
                            position: relative;
                            &:after {
                                content: '';
                                border-bottom: 2px solid red;
                                position: absolute;
                                left: 0;
                                top: 50%;
                                width: 100%;
                            }
                        }
                    }
                }
            }

            .group {
                display: flex;
            }
            .tag-input-wrapper input {
                width: auto;
            }
        }
    }




</style>