import Vue from 'vue'
import Vuex from 'vuex'
import VueResource from 'vue-resource'
import api from './api.js'
Vue.use(Vuex)
Vue.use(VueResource)

export default new Vuex.Store({
  modules: {
  },
  middlewares: api
})
