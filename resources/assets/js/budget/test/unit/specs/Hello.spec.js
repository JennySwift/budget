import Vue from 'vue'
import HelloWorld from '@/components/HelloWorld'


global.$ = require('jquery');
global.jQuery = require('jquery');
global.store = require('../../../src/repositories/Store');
const bus = new Vue();
Vue.prototype.$bus = bus;

describe('HelloWorld.vue', () => {
  it('should render correct contents', () => {
    const Constructor = Vue.extend(HelloWorld)
    const vm = new Constructor().$mount()
    expect(vm.$el.querySelector('.hello h1').textContent)
      .to.equal('Welcome to Your Vue.js App')
  })
})
