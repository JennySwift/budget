// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'

global.$ = require('jquery');
global.jQuery = require('jquery');
global._ = require('lodash');
import store from './repositories/Store'
import TotalsRepository from './repositories/TotalsRepository'
import FilterRepository from './repositories/FilterRepository'
window.store = store;
window.Event = new Vue();
const bus = new Vue()
Vue.prototype.$bus = bus


import PopupComponent from './components/shared/PopupComponent.vue'
import DropdownComponent from './components/shared/DropdownComponent.vue'
import AutocompleteComponent from './components/shared/AutocompleteComponent.vue'
import TransactionAutocompleteComponent from './components/shared/TransactionAutocompleteComponent.vue'
import DropdownArrowComponent from './components/shared/DropdownArrowComponent.vue'

//Directives
import SlideDirective from './directives/SlideDirective.js'

Vue.config.productionTip = false

//Directives
Vue.directive('slide', SlideDirective);

//Components
Vue.component('autocomplete', AutocompleteComponent);
Vue.component('dropdown', DropdownComponent);
Vue.component('dropdown-arrow', DropdownArrowComponent);
Vue.component('popup', PopupComponent);


/* eslint-disable no-new */
const app = new Vue({
    el: '#app',
    router,
    template: '<App/>',
    components: {
        App
    }
})

store.getUser();
store.getEnvironment();
store.getAccounts();
store.setHeights();
store.getBudgets();
store.getUnassignedBudgets();
store.getFavouriteTransactions();
store.setDefaultTab();
TotalsRepository.getSideBarTotals();
store.getSavedFilters();
FilterRepository.resetFilter();
store.setDefaultTransactionPropertiesToShow()