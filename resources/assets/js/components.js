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
import TransactionAutocompleteComponent from './budget/src/components/shared/TransactionAutocompleteComponent.vue'
import NewTransactionComponent from './budget/src/components/transactions/NewTransactionComponent.vue'
import TotalsComponent from './budget/src/components/TotalsComponent.vue'
import TransactionsComponent from './budget/src/components/transactions/TransactionsComponent.vue'
import TransactionComponent from './budget/src/components/transactions/TransactionComponent.vue'
import MassTransactionUpdatePopupComponent from './budget/src/components/filter/MassTransactionUpdatePopupComponent.vue'
import EditTransactionPopupComponent from './budget/src/components/transactions/EditTransactionPopupComponent.vue'
import AllocationPopupComponent from './budget/src/components/budgets/AllocationPopupComponent.vue'
import EditAccountComponent from './budget/src/components/accounts/EditAccountComponent.vue'
import NewAccountComponent from './budget/src/components/accounts/NewAccountComponent.vue'
import EditBudgetPopupComponent from './budget/src/components/budgets/EditBudgetPopupComponent.vue'
import NewBudgetComponent from './budget/src/components/budgets/NewBudgetComponent.vue'
import BudgetsToolbarComponent from './budget/src/components/shared/BudgetsToolbarComponent.vue'
import FixedBudgetsTableComponent from './budget/src/components/budgets/FixedBudgetsTable.vue'
import FilterComponent from './budget/src/components/filter/FilterComponent.vue'

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
Vue.component('transaction', TransactionComponent);
Vue.component('mass-transaction-update-popup', MassTransactionUpdatePopupComponent);
Vue.component('edit-transaction-popup', EditTransactionPopupComponent);
Vue.component('allocation-popup', AllocationPopupComponent);
Vue.component('transaction-autocomplete', TransactionAutocompleteComponent);
Vue.component('edit-account', EditAccountComponent);
Vue.component('new-account', NewAccountComponent);
Vue.component('edit-budget-popup', EditBudgetPopupComponent);
Vue.component('new-budget', NewBudgetComponent);
Vue.component('budgets-toolbar', BudgetsToolbarComponent);
Vue.component('fixed-budgets-table', FixedBudgetsTableComponent);
Vue.component('transactions-filter', FilterComponent);
