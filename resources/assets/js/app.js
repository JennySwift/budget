import Vue from 'vue'
import router from './router'

global.$ = require('jquery');
global.jQuery = require('jquery');

global._ = require('lodash');

import store from './repositories/Store'
window.store = store;

require('./components');
require('./directives');
require('./method-calls');

window.Event = new Vue();
const bus = new Vue()
Vue.prototype.$bus = bus

const app = new Vue({
    router
}).$mount('#app')



