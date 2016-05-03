import Vue from 'vue'
import VueRouter from 'vue-router'
import Vuex from 'vuex'
import App from './App'
import Hello from './components/Hello.vue'

Vue.use(Vuex)
Vue.use(VueRouter)
var router = new VueRouter()

router.map({
  '/': {
    component: Hello
  }
})

// Now we can start the app!
// The router will create an instance of App and mount to
// the element matching the selector #app.
router.start(App, '#app')
