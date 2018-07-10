import Vue from 'vue'

import PopupComponent from './components/shared/PopupComponent.vue'
import DropdownComponent from './components/shared/DropdownComponent.vue'
import AutocompleteComponent from './components/shared/AutocompleteComponent.vue'
import DropdownArrowComponent from './components/shared/DropdownArrowComponent.vue'
import NavbarComponent from './components/shared/NavbarComponent.vue'
import LoadingComponent from './components/shared/LoadingComponent.vue'
import FeedbackComponent from './components/shared/FeedbackComponent.vue'

Vue.component('autocomplete', AutocompleteComponent);
Vue.component('dropdown', DropdownComponent);
Vue.component('dropdown-arrow', DropdownArrowComponent);
Vue.component('popup', PopupComponent);
Vue.component('navbar', NavbarComponent);
Vue.component('feedback', FeedbackComponent);
Vue.component('loading', LoadingComponent);