import Vue from 'vue'
import router from './router'

global.$ = require('jquery');
global.jQuery = require('jquery');

global._ = require('lodash');
import store from './budget/src/repositories/Store'
import TotalsRepository from './budget/src/repositories/TotalsRepository'
import FilterRepository from './budget/src/repositories/FilterRepository'
window.store = store;

window.Event = new Vue();

import PopupComponent from './budget/src/components/shared/PopupComponent.vue'
import DropdownComponent from './budget/src/components/shared/DropdownComponent.vue'
import AutocompleteComponent from './budget/src/components/shared/AutocompleteComponent.vue'
import DropdownArrowComponent from './budget/src/components/shared/DropdownArrowComponent.vue'
import NavbarComponent from './budget/src/components/shared/NavbarComponent.vue'
import LoadingComponent from './budget/src/components/shared/LoadingComponent.vue'
import FeedbackComponent from './budget/src/components/shared/FeedbackComponent.vue'
import SlideDirective from './budget/src/directives/SlideDirective.js'

Vue.directive('slide', SlideDirective);
Vue.component('autocomplete', AutocompleteComponent);
Vue.component('dropdown', DropdownComponent);
Vue.component('dropdown-arrow', DropdownArrowComponent);
Vue.component('popup', PopupComponent);
Vue.component('navbar', NavbarComponent);
Vue.component('feedback', FeedbackComponent);
Vue.component('loading', LoadingComponent);

const bus = new Vue()
Vue.prototype.$bus = bus

const app = new Vue({
    router
}).$mount('#app')

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

$(window).on('load', function () {
    // $(".main").css('display', 'block');
    // $("footer, #navbar").css('display', 'flex');
    // $("#page-loading").hide();
    //Uncomment after refactor
    // smoothScroll.init();
});



