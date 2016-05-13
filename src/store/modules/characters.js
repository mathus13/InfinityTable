import Vue from 'vue'
import Vuex from 'vuex'
import VueResource from 'vue-resource'
Vue.use(Vuex)
Vue.use(VueResource)

const state = {
  resource: 'characters',
  loaded: false,
  characters: {}
}

const mutations = {
  CHARACTERS_SET (state, characters) {
    state.characters = characters
  },
  CHARACTERS_COMMIT () {
  },
  CHARACTERS_COMMIT_RESPONSE (state, character) {
    state.characters.push(character)
  },
  CHARACTERS_DELETE () {

  },
  CHARACTERS_COMMIT_DELETE (state, character) {
    var index
    for (index in state.characters) {
      if (state.characters[index].id == character.id) {
        state.characters.splice(index, 1)
        return
      }
    }
  }
}

export default {
  state,
  mutations
}
