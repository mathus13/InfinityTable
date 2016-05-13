import Vue from 'vue'
import Vuex from 'vuex'
import VueResource from 'vue-resource'
Vue.use(Vuex)
Vue.use(VueResource)

const state = {
  resource: 'users',
  loaded: false,
  users: {}
}

const mutations = {
  USERS_SET (state, users) {
    state.users = users
  },
  USERS_COMMIT () {
  },
  USERS_COMMIT_RESPONSE (state, user) {
    state.users.push(user)
  },
  USERS_DELETE () {

  },
  USERS_COMMIT_DELETE (state, user) {
    var index
    for (index in state.users) {
      if (state.users[index].id == user.id) {
        state.users.splice(index, 1)
        return
      }
    }
  }
}

export default {
  state,
  mutations
}
