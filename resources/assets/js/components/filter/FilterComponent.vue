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

                        <!--Accounts-->
                        <filter-section
                            :filter-tab="filterTab"
                            label="Accounts"
                        >
                            <template slot="content">
                                <!--Show-->
                                <div v-show="filterTab === 'show'">

                                    <div class="checkbox-container">
                                        <input
                                            v-model="shared.filter.accounts.in"
                                            value="all"
                                            type="checkbox"
                                            id="accounts-filter-in-all"
                                        >
                                        <label for="accounts-filter-in-all">all</label>
                                    </div>

                                    <div class="checkbox-container">
                                        <input
                                            v-model="shared.filter.accounts.in"
                                            value="none"
                                            type="checkbox"
                                            id="accounts-filter-in-none"
                                        >
                                        <label for="accounts-filter-in-none">none</label>
                                    </div>

                                    <div v-for="account in shared.accounts" class="checkbox-container">
                                        <input
                                            type="checkbox"
                                            :id="account.name"
                                            :value="account.id"
                                            :disabled="shared.filter.accounts.out.indexOf(account.id) !== -1"
                                            v-model="shared.filter.accounts.in"
                                            v-on:change="runFilter()"
                                        >
                                        <label
                                            :for="account.name"
                                            v-bind:class="{'disabled': shared.filter.accounts.out.indexOf(account.id) !== -1}"
                                        >
                                            {{account.name}}
                                        </label>
                                    </div>

                                </div>

                                <!--Hide-->
                                <div v-show="filterTab === 'hide'">

                                    <div v-for="account in shared.accounts" class="checkbox-container">
                                        <input
                                            type="checkbox"
                                            :id="account.name"
                                            :value="account.id"
                                            :disabled="shared.filter.accounts.in.indexOf(account.id) !== -1"
                                            v-model="shared.filter.accounts.out"
                                            v-on:change="runFilter()"
                                        >
                                        <label
                                            :for="account.name"
                                            v-bind:class="{'disabled': shared.filter.accounts.in.indexOf(account.id) !== -1}"
                                        >
                                            {{account.name}}
                                        </label>
                                    </div>

                                </div>
                            </template>
                        </filter-section>

                        <!--Types-->
                        <filter-section
                            :filter-tab="filterTab"
                            label="Types"
                        >
                            <template slot="content">
                                <div>
                                    <div class="checkbox-container">
                                        <input type="checkbox" id="filter-in-all-types">
                                        <label for="filter-in-all-types">all</label>
                                    </div>

                                    <div class="checkbox-container">
                                        <input id="filter-in-no-types" type="checkbox">
                                        <label for="filter-in-no-types">none</label>
                                    </div>

                                    <div
                                        v-show="filterTab === 'show'"
                                        v-if="shared.filter.types"
                                        v-for="type in types"
                                        class="checkbox-container"
                                    >

                                        <input
                                            v-model="shared.filter.types.in"
                                            v-on:change="runFilter()"
                                            :id="type"
                                            :value="type"
                                            :disabled="shared.filter.types.out.indexOf(type) !== -1"
                                            type="checkbox"
                                        >
                                        <label
                                            :for="type"
                                            v-bind:class="{'disabled': shared.filter.types.out.indexOf(type) !== -1}"
                                        >
                                            {{type}}
                                        </label>

                                    </div>

                                    <div
                                        v-show="filterTab === 'hide'"
                                        v-if="shared.filter.types"
                                        v-for="type in types"
                                        class="checkbox-container"
                                    >
                                        <input
                                            v-model="shared.filter.types.out"
                                            v-on:change="runFilter()"
                                            :id="type + '-out'"
                                            :value="type"
                                            :disabled="shared.filter.types.in.indexOf(type) !== -1"
                                            type="checkbox"
                                        >
                                        <label
                                            :for="type + '-out'"
                                            v-bind:class="{'disabled': shared.filter.types.in.indexOf(type) !== -1}"
                                        >
                                            {{type}}
                                        </label>

                                    </div>
                                </div>
                            </template>
                        </filter-section>

                        <!--Description-->
                        <filter-section
                            :filter-tab="filterTab"
                            label="Description"
                        >
                            <template slot="filterIn">
                                <label for="filter-description-in">Filter description in</label>
                                <filter-input-field field="description" type="in" id="filter-description-in" :model="shared.filter.description.in" path="filter.description.in"></filter-input-field>
                            </template>
                            <template slot="filterOut">
                                <label for="filter-description-out">Filter description out</label>
                                <filter-input-field field="description" type="out" id="filter-description-out" :model="shared.filter.description.out" path="filter.description.out"></filter-input-field>
                            </template>
                        </filter-section>

                        <!--Merchant-->
                        <filter-section
                            :filter-tab="filterTab"
                            label="Merchant"
                        >
                            <template slot="filterIn">
                                <label for="filter-merchant-in">Filter merchant in</label>
                                <filter-input-field field="merchant" type="in" id="filter-merchant-in" :model="shared.filter.merchant.in" path="filter.merchant.in"></filter-input-field>
                            </template>
                            <template slot="filterOut">
                                <label for="filter-merchant-out">Filter merchant out</label>
                                <filter-input-field field="merchant" type="out" id="filter-merchant-out" :model="shared.filter.merchant.out" path="filter.merchant.out"></filter-input-field>
                            </template>
                        </filter-section>

                        <!--Total-->
                        <filter-section
                            :filter-tab="filterTab"
                            label="Total"
                        >
                            <template slot="filterIn">
                                <label for="filter-total-in">Filter in by total (negative sign required for expenses)</label>
                                <filter-input-field field="total" type="in" id="filter-total-in" :model="shared.filter.total.in" path="filter.total.in"></filter-input-field>
                            </template>
                            <template slot="filterOut">
                                <label for="filter-total-out">Filter out by total (negative sign required for expenses)</label>
                                <filter-input-field field="total" type="out" id="filter-total-out" :model="shared.filter.total.out" path="filter.total.out"></filter-input-field>
                            </template>
                        </filter-section>

                        <!--Budgets-->
                        <filter-section
                            :filter-tab="filterTab"
                            label="Budgets"
                        >
                           <template slot="content">
                               <div v-show="filterTab === 'show'">

                                   <div class="form-group">

                                       <label>Transactions will contain all of the budgets entered here</label>

                                       <div class="input-btn-container">
                                           <autocomplete
                                               autocomplete-id="filter-budgets-in-and-autocomplete"
                                               input-id="filter-budgets-in-and-input"
                                               :unfiltered-options="shared.budgets"
                                               prop="name"
                                               multiple-selections="true"
                                           >
                                           </autocomplete>

                                           <span class="input-group-btn">
                            <button v-on:click="clearBudgetField('in', 'and')" class="clear-search-button btn btn-default">clear</button>
                        </span>
                                       </div>

                                   </div>

                                   <div class="form-group">

                                       <label>Transactions will contain at least one of the budgets entered here</label>

                                       <div
                                           class="input-btn-container"
                                       >
                                           <autocomplete
                                               autocomplete-id="filter-budgets-in-or-autocomplete"
                                               input-id="filter-budgets-in-or-input"
                                               :unfiltered-options="shared.budgets"
                                               prop="name"
                                               multiple-selections="true"
                                           >
                                           </autocomplete>

                                           <span class="input-group-btn">
                            <button v-on:click="clearBudgetField('in', 'or')" class="clear-search-button btn btn-default">clear</button>
                        </span>
                                       </div>

                                   </div>

                               </div>
                               <div v-show="filterTab === 'hide'">

                                   <div class="form-group">

                                       <label>Transactions will contain none of the budgets entered here</label>

                                       <div class="input-btn-container">
                                           <autocomplete
                                               autocomplete-id="filter-budgets-out-autocomplete"
                                               input-id="filter-budgets-out-input"
                                               :unfiltered-options="shared.budgets"
                                               prop="name"
                                               multiple-selections="true"
                                           >
                                           </autocomplete>

                                           <span class="input-group-btn">
                        <button v-on:click="clearBudgetField('out')" class="clear-search-button btn btn-default">clear</button>
                    </span>
                                       </div>

                                   </div>
                               </div>
                           </template>
                        </filter-section>

                        <!--Dates-->
                        <filter-section
                            :filter-tab="filterTab"
                            label="Date"
                        >
                           <template slot="content">
                               <!--Single date in-->
                               <div
                                   v-show="filterTab === 'show'"
                                   v-if="shared.filter.singleDate"
                                   class="form-group"
                               >
                                   <label for="filter-single-date-in">Filter in by date</label>

                                   <filter-input-field field="singleDate" type="in" id="filter-single-date-in" :model="shared.filter.singleDate.in" path="filter.singleDate.in"></filter-input-field>
                               </div>

                               <!--Single date out-->
                               <div
                                   v-show="filterTab === 'hide'"
                                   v-if="shared.filter.singleDate"
                                   class="form-group"
                               >
                                   <label for="filter-single-date-out">Filter out by date</label>

                                   <filter-input-field field="singleDate" type="out" id="filter-single-date-out" :model="shared.filter.singleDate.out" path="filter.singleDate.out"></filter-input-field>
                               </div>

                               <!--From date in-->
                               <div
                                   v-show="filterTab === 'show'"
                                   v-if="shared.filter.fromDate"
                                   class="form-group"
                               >
                                   <label for="filter-from-date-in">Filter in by date on or after...</label>

                                   <filter-input-field field="fromDate" type="in" id="filter-from-date-in" :model="shared.filter.fromDate.in" path="filter.fromDate.in"></filter-input-field>
                               </div>

                               <!--To date in-->
                               <div
                                   v-show="filterTab === 'show'"
                                   v-if="shared.filter.toDate"
                                   class="form-group"
                               >
                                   <label for="filter-to-date-in">Filter in by date on or before...</label>

                                   <filter-input-field field="toDate" type="in" id="filter-to-date-in" :model="shared.filter.toDate.in" path="filter.toDate.in"></filter-input-field>
                               </div>
                           </template>
                        </filter-section>

                        <!--Reconciled-->
                        <filter-section
                            :filter-tab="filterTab"
                            label="Reconciled"
                        >
                            <template slot="content">
                                <div class="radio">
                                    <label>
                                        <input
                                            v-model="shared.filter.reconciled"
                                            v-on:change="runFilter()"
                                            type="radio"
                                            name="status"
                                            value="any"
                                        >
                                        Any
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input
                                            v-model="shared.filter.reconciled"
                                            v-on:change="runFilter()"
                                            type="radio"
                                            name="status"
                                            value="true"
                                        >
                                        Reconciled
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input
                                            v-model="shared.filter.reconciled"
                                            v-on:change="runFilter()"
                                            type="radio"
                                            name="status"
                                            value="false"
                                        >
                                        Unreconciled
                                    </label>
                                </div>

                            </template>
                        </filter-section>

                        <!--Invalid Allocation-->
                        <filter-section
                            :filter-tab="filterTab"
                            label="Number of Budgets"
                        >
                            <template slot="content">
                                <div class="radio">
                                    <label>
                                        <input
                                            v-model="shared.filter.invalidAllocation"
                                            v-on:change="runFilter()"
                                            type="radio"
                                            name="invalid-allocation"
                                            value="false"
                                        >
                                        Any
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input
                                            v-model="shared.filter.invalidAllocation"
                                            v-on:change="runFilter()"
                                            type="radio"
                                            name="invalid-allocation"
                                            value="true"
                                        >
                                        Invalid
                                    </label>
                                </div>
                            </template>
                        </filter-section>

                        <!--Number of Budgets-->
                        <filter-section
                            :filter-tab="filterTab"
                            label="Number of Budgets"
                        >
                            <template slot="content">
                                <div v-show="filterTab === 'show'">

                                    <!--Filter in all-->
                                    <div class="radio inline">
                                        <label>
                                            <input
                                                v-model="shared.filter.numBudgets.in"
                                                v-on:change="runFilter()"
                                                type="radio"
                                                name="filter-num-budgets-in"
                                                value="all"
                                            >
                                            Any number of budgets
                                        </label>
                                    </div>

                                    <!--Filter in transactions with no budgets-->
                                    <div class="radio">
                                        <label>
                                            <input
                                                v-model="shared.filter.numBudgets.in"
                                                v-on:change="runFilter()"
                                                type="radio"
                                                name="filter-num-budgets-in"
                                                value="zero"
                                            >
                                            No budgets
                                        </label>
                                    </div>

                                    <!--Filter in transactions with single budgets-->
                                    <div class="radio">
                                        <label>
                                            <input
                                                v-model="shared.filter.numBudgets.in"
                                                v-on:change="runFilter()"
                                                type="radio"
                                                name="filter-num-budgets-in"
                                                value="single"
                                            >
                                            One budget
                                        </label>
                                    </div>

                                    <!--Filter in transactions with multiple budgets-->
                                    <div class="radio">
                                        <label>
                                            <input
                                                v-model="shared.filter.numBudgets.in"
                                                v-on:change="runFilter()"
                                                type="radio"
                                                name="filter-num-budgets-in"
                                                value="multiple"
                                            >
                                            Multiple budgets
                                        </label>
                                    </div>

                                </div>
                                <div v-show="filterTab === 'hide'">

                                    <!--Filter out none-->
                                    <div class="radio">
                                        <label>
                                            <input
                                                v-model="shared.filter.numBudgets.out"
                                                v-on:change="runFilter()"
                                                type="radio"
                                                name="filter-num-budgets-out"
                                                value="none"
                                            >
                                            Do not filter out by number of budgets
                                        </label>
                                    </div>

                                    <!--Filter out transactions with no budgets-->
                                    <div class="radio">
                                        <label>
                                            <input
                                                v-model="shared.filter.numBudgets.out"
                                                v-on:change="runFilter()"
                                                type="radio"
                                                name="filter-num-budgets-out"
                                                value="zero"
                                            >
                                            No budgets
                                        </label>
                                    </div>

                                    <!--Filter out transactions with single budgets-->
                                    <div class="radio">
                                        <label>
                                            <input
                                                v-model="shared.filter.numBudgets.out"
                                                v-on:change="runFilter()"
                                                type="radio"
                                                name="filter-num-budgets-out"
                                                value="single"
                                            >
                                            One budget
                                        </label>
                                    </div>

                                    <!--Filter out transactions with multiple budgets-->
                                    <div class="radio">
                                        <label>
                                            <input
                                                v-model="shared.filter.numBudgets.out"
                                                v-on:change="runFilter()"
                                                type="radio"
                                                name="filter-num-budgets-out"
                                                value="multiple"
                                            >
                                            Multiple budgets
                                        </label>
                                    </div>

                                </div>
                            </template>
                        </filter-section>

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
    import FilterSectionComponent from '../../components/filter/FilterSectionComponent.vue'
    export default {
        data: function () {
            return {
                shared: store.state,
                filterTab: 'show',
                filterRepository: FilterRepository.state,
                types: ['income', 'expense', 'transfer']
            };
        },
        components: {
            'saved-filters': SavedFiltersComponent,
            'toolbar-for-filter': ToolbarForFilterComponent,
            'totals-for-filter': TotalsForFilterComponent,
            'filter-section': FilterSectionComponent,
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

            /**
             * type1 is 'in' or 'out'.
             * type2 is 'and' or 'or'.
             * @param type1
             * @param type2
             */
            clearBudgetField: function (type1, type2) {
                FilterRepository.clearBudgetField(this, type1, type2);
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
        },
        mounted: function () {
            setTimeout(function () {
                FilterRepository.runFilter();
            }, 100);
        },
        events: {
            'budget-chosen': function () {
                store.runFilter();
            },
            'budget-removed': function () {
                store.runFilter();
            }
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
                    background: black;
                    /*color: #CFCFCF;*/
                    /*cursor: pointer;*/
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