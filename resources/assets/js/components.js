import Vue from 'vue'

// var vueTippy = require('vue-tippy');
// Vue.use(vueTippy);

import NavbarComponent from './budget/src/components/shared/NavbarComponent.vue'
import LoadingComponent from './budget/src/components/shared/LoadingComponent.vue'
import PopupComponent from './budget/src/components/shared/PopupComponent.vue'
import DropdownComponent from './budget/src/components/shared/DropdownComponent.vue'
import BudgetAutocompleteComponent from './budget/src/components/shared/BudgetAutocompleteComponent.vue'
// import NewPopupComponent from './budget/src/components/shared/NewPopupComponent.vue'
// import ButtonsComponent from './budget/src/components/shared/ButtonsComponent.vue'
import AutocompleteComponent from './budget/src/components/shared/AutocompleteComponent.vue'
import FeedbackComponent from './budget/src/components/shared/FeedbackComponent.vue'
import NewTransactionComponent from './budget/src/components/NewTransactionComponent.vue'
import TotalsComponent from './budget/src/components/TotalsComponent.vue'
import TransactionsComponent from './budget/src/components/TransactionsComponent.vue'
import MassTransactionUpdatePopupComponent from './budget/src/components/filter/MassTransactionUpdatePopupComponent.vue'
import EditTransactionPopupComponent from './budget/src/components/EditTransactionPopupComponent.vue'
import AllocationPopupComponent from './budget/src/components/AllocationPopupComponent.vue'

//Shared components
Vue.component('navbar', NavbarComponent);
Vue.component('feedback', FeedbackComponent);
Vue.component('loading', LoadingComponent);
Vue.component('popup', PopupComponent);
Vue.component('autocomplete', AutocompleteComponent);
Vue.component('dropdown', DropdownComponent);
Vue.component('budget-autocomplete', BudgetAutocompleteComponent);

// Components
Vue.component('new-transaction', NewTransactionComponent);
Vue.component('totals', TotalsComponent);
Vue.component('transactions', TransactionsComponent);
Vue.component('mass-transaction-update-popup', MassTransactionUpdatePopupComponent);
Vue.component('edit-transaction-popup', EditTransactionPopupComponent);
Vue.component('allocation-popup', AllocationPopupComponent);
