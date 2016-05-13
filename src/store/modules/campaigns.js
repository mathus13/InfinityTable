import Vue from 'vue'
import Vuex from 'vuex'
import VueResource from 'vue-resource'
Vue.use(Vuex)
Vue.use(VueResource)

const state = {
  resource: 'campaigns',
  loaded: false,
  campaigns: {}
}

const mutations = {
  CAMPAIGNS_SET (state, campaigns) {
    state.campaigns = campaigns
  },
  CAMPAIGNS_COMMIT () {
  },
  CAMPAIGNS_COMMIT_RESPONSE (state, campaign) {
    state.campaigns.push(campaign)
  },
  CAMPAIGNS_DELETE () {

  },
  CAMPAIGNS_COMMIT_DELETE (state, campaign) {
    var index
    for (index in state.campaigns) {
      if (state.campaigns[index].id == campaign.id) {
        state.campaigns.splice(index, 1)
        return
      }
    }
  }
}

export default {
  state,
  mutations
}
