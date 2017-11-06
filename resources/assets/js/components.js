import Vue from 'vue'

import PopupComponent from './budget/src/components/shared/PopupComponent.vue'
import DropdownComponent from './budget/src/components/shared/DropdownComponent.vue'
import AutocompleteComponent from './budget/src/components/shared/AutocompleteComponent.vue'
import DropdownArrowComponent from './budget/src/components/shared/DropdownArrowComponent.vue'
import NavbarComponent from './budget/src/components/shared/NavbarComponent.vue'
import LoadingComponent from './budget/src/components/shared/LoadingComponent.vue'
import FeedbackComponent from './budget/src/components/shared/FeedbackComponent.vue'

Vue.component('autocomplete', AutocompleteComponent);
Vue.component('dropdown', DropdownComponent);
Vue.component('dropdown-arrow', DropdownArrowComponent);
Vue.component('popup', PopupComponent);
Vue.component('navbar', NavbarComponent);
Vue.component('feedback', FeedbackComponent);
Vue.component('loading', LoadingComponent);