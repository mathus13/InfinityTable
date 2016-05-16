import Vue from 'vue'
import Vuex from 'vuex'
import VueResource from 'vue-resource'
Vue.use(Vuex)
Vue.use(VueResource)

const state = {
  resource: 'sessions',
  loaded: false,
  sessions: {}
}

const mutations = {
  SESSIONS_SET (state, sessions) {
    state.sessions = sessions
  },
  SESSIONS_FETCH () {
  },
  SESSIONS_COMMIT () {
  },
  SESSIONS_COMMIT_RESPONSE (state, group) {
    state.sessions.push(group)
  },
  SESSIONS_DELETE () {

  },
  SESSIONS_COMMIT_DELETE (state, group) {
    var index
    for (index in state.sessions) {
      if (state.sessions[index].id == group.id) {
        state.sessions.splice(index, 1)
        return
      }
    }
  }
}

export default {
  state,
  mutations
}
