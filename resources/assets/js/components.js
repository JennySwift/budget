import Vue from 'vue'

// var vueTippy = require('vue-tippy');
// Vue.use(vueTippy);

import NavbarComponent from './budget/src/components/shared/NavbarComponent.vue'
import LoadingComponent from './budget/src/components/shared/LoadingComponent.vue'
import PopupComponent from './budget/src/components/shared/PopupComponent.vue'
// import NewPopupComponent from './budget/src/components/shared/NewPopupComponent.vue'
// import ButtonsComponent from './budget/src/components/shared/ButtonsComponent.vue'
import AutocompleteComponent from './budget/src/components/shared/AutocompleteComponent.vue'
import FeedbackComponent from './budget/src/components/shared/FeedbackComponent.vue'
import NewTransactionComponent from './budget/src/components/NewTransactionComponent.vue'
import TotalsComponent from './budget/src/components/TotalsComponent.vue'
import TransactionsComponent from './budget/src/components/TransactionsComponent.vue'
import MassTransactionUpdatePopupComponent from './budget/src/components/filter/MassTransactionUpdatePopupComponent.vue'

//Shared components
Vue.component('navbar', NavbarComponent);
Vue.component('feedback', FeedbackComponent);
Vue.component('loading', LoadingComponent);
Vue.component('popup', PopupComponent);
Vue.component('autocomplete', AutocompleteComponent);

// Components
Vue.component('new-transaction', NewTransactionComponent);
Vue.component('totals', TotalsComponent);
Vue.component('transactions', TransactionsComponent);
Vue.component('mass-transaction-update-popup', MassTransactionUpdatePopupComponent);
