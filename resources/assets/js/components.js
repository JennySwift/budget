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

//Shared components
Vue.component('navbar', NavbarComponent);
Vue.component('feedback', FeedbackComponent);
Vue.component('loading', LoadingComponent);
Vue.component('popup', PopupComponent);
Vue.component('autocomplete', AutocompleteComponent);

// Components