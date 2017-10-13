import TransactionsPageComponent from './budget/src/components/TransactionsPageComponent.vue'
import AccountsPageComponent from './budget/src/components/AccountsPageComponent.vue'
import FixedBudgetsPageComponent from './budget/src/components/FixedBudgetsPageComponent.vue'
import FlexBudgetsPageComponent from './budget/src/components/FlexBudgetsPageComponent.vue'
import UnassignedBudgetsPageComponent from './budget/src/components/UnassignedBudgetsPageComponent.vue'
import GraphsPageComponent from './budget/src/components/filter/GraphsPageComponent.vue'
import FavouriteTransactionsPageComponent from './budget/src/components/FavouriteTransactionsComponent.vue'
import HelpPageComponent from './budget/src/components/HelpPageComponent.vue'
import PreferencesPageComponent from './budget/src/components/PreferencesComponent.vue'
import FeedbackPageComponent from './budget/src/components/FeedbackPageComponent.vue'

export default [
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

