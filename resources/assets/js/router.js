import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter);

import TransactionsPageComponent from './components/transactions/TransactionsPageComponent.vue'
import AccountsPageComponent from './components/accounts/AccountsPageComponent.vue'
import FixedBudgetsPageComponent from './components/budgets/FixedBudgetsPageComponent.vue'
import FlexBudgetsPageComponent from './components/budgets/FlexBudgetsPageComponent.vue'
import UnassignedBudgetsPageComponent from './components/budgets/UnassignedBudgetsPageComponent.vue'
import GraphsPageComponent from './components/filter/GraphsPageComponent.vue'
import FavouriteTransactionsPageComponent from './components/favourite_transactions/FavouriteTransactionsComponent.vue'
import HelpPageComponent from './components/HelpPageComponent.vue'
import PreferencesPageComponent from './components/PreferencesComponent.vue'
import FeedbackPageComponent from './components/FeedbackPageComponent.vue'

export default new VueRouter({
    routes: [
        {
            path: '/',
            component: TransactionsPageComponent
        },
        {
            path: '/accounts',
            component: AccountsPageComponent
        },
        {
            path: '/fixed-budgets',
            component: FixedBudgetsPageComponent
        },
        {
            path: '/flex-budgets',
            component: FlexBudgetsPageComponent
        },
        {
            path: '/unassigned-budgets',
            component: UnassignedBudgetsPageComponent
        },
        {
            path: '/graphs',
            component: GraphsPageComponent
        },
        {
            path: '/favourite-transactions',
            component: FavouriteTransactionsPageComponent
        },


        {
            path: '/help',
            component: HelpPageComponent
        },
        {
            path: '/preferences',
            component: PreferencesPageComponent
        },
        {
            path: '/feedback',
            component: FeedbackPageComponent
        },
    ]
})

