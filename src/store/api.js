import Vue from 'vue'
import Vuex from 'vuex'
import VueResource from 'vue-resource'
import {clientsSet} from './actions.js'
import {appoitnmentsSet} from './actions.js'
import {sitesSet} from './actions.js'
Vue.use(Vuex)
Vue.use(VueResource)

var site = {}
var method = 'POST'
var prefix = ''
var baseURL = 'http://rpg.sbarratt/api/'

var apiCommit = function (mutation, state, store) {

  var url = baseURL
  var resource = mutation.payload[1]

  site = mutation.payload[0]
  url += resource
  prefix = resource.toUpperCase()
  if (undefined !== site.id) {

    method = 'PUT'
    url = url + '/' + site.id

  }

  Vue.http({url: url, method: method, data: site}).then(function (response) {

    switch (response.status) {

      case '201':
        store.dispatch(prefix + '_COMMIT_RESPONSE', response.data.data)
        break
      default:
        break;

    }

  })

}

var api_get = function (mutation, state, store) {
  console.log(mutation);
  var url = baseURL,
    resource = mutation.payload[0],
    apiReturn
  url += resource
  prefix = resource.toUpperCase()
  Vue.http({url: url, method: 'GET'}).then(function (response) {
    var items = []
    switch (response.status) {
      case 200:
        var apiReturn = response.data
        for (site of apiReturn.data) {

          if (site.hasOwnProperty('id')) {

            items.push(site.attributes)

          }

        }
        break
    }
    switch (prefix) {
      case 'CAMPAIGNS':
        campaignsSet(items)
        break
      case 'CHARACTERS':
        charactersSet(items)
        break
      case 'GROUPS':
        groupsSet(items)
        break
      case 'SESSIONS':
        sessionsSet(items)
        break
      case 'USERS':
        usersSet(items)
        break
      default:
        // ignore
    }

  })

}

var api_delete = function (mutation, state, store) {
  var url = baseURL
  var resource = mutation.payload[1]
  site = mutation.payload[0]
  if (undefined === site.id) {
    return
  }
  prefix = resource.toUpperCase()
  url += resource + '/' + site.id
  method = 'DELETE'
  Vue.http({url: url, method: method}).then(function (response) {
    switch (response.status) {
      case 200:
        store.dispatch(prefix + '_COMMIT_DELETE', site)
        break
    }
  })
}

const apiMiddleware = {
  onInit (state) {
//    api_get(state)
  },
  onMutation (mutation, state, store) {
    switch (mutation.type) {
      case 'CAMPAIGNS_FETCH':
      case 'CHARACTERS_FETCH':
      case 'GROUPS_FETCH':
      case 'SESSIONS_FETCH':
      case 'USERS_FETCH':
        api_get(mutation, state, store)
        break
      case 'CAMPAIGNS_COMMIT':
      case 'GROUPS_COMMIT':
      case 'CHARACTERS_COMMIT':
      case 'SESSIONS_COMMIT':
      case 'USERS_COMMIT':
        apiCommit(mutation, state, store)
        break
      case 'CAMPAIGNS_DELETE':
      case 'GROUPS_DELETE':
      case 'CHARACTERS_DELETE':
      case 'SESSIONS_DELETE':
      case 'USERS_DELETE':
        api_delete(mutation, state, store)
        break
    }
  }
}
export default [apiMiddleware]
