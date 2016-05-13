import Vue from 'vue'
import Vuex from 'vuex'
import VueResource from 'vue-resource'
Vue.use(Vuex)
Vue.use(VueResource)

const state = {
  resource: 'groups',
  loaded: false,
  groups: {}
}

const mutations = {
  GROUPS_SET (state, groups) {
    state.groups = groups
  },
  GROUPS_COMMIT () {
  },
  GROUPS_COMMIT_RESPONSE (state, group) {
    state.groups.push(group)
  },
  GROUPS_DELETE () {

  },
  GROUPS_COMMIT_DELETE (state, group) {
    var index
    for (index in state.groups) {
      if (state.groups[index].id == group.id) {
        state.groups.splice(index, 1)
        return
      }
    }
  }
}

export default {
  state,
  mutations
}
