import Vue from 'vue'
import VueRouter from 'vue-router'
import App from './App.vue'
import Hello from './components/Hello.vue'
import Sessions from './components/Sessions.vue'

Vue.use(VueRouter)
var router = new VueRouter()

router.map({
  '/': {
    component: Hello
  },
  '/sessions': {
    component: Sessions
  }
})
router.start(App, '#app')
