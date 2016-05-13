import Vue from 'vue'
import Vuex from 'vuex'
import VueResource from 'vue-resource'
import {campaignsSet} from './actions.js'
import {usersSet} from './actions.js'
import {groupsSet} from './actions.js'
import {sessionsSet} from './actions.js'
import {charactersSet} from './actions.js'
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

var api_get = function (mutation) {
  var url = baseURL,
    resource = mutation.payload[0]

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
  onInit () {
//    api_get(state)
  },
  onMutation (mutation, state, store) {
    switch (true) {
      case mutation.type.indexOf('_FETCH') :
        api_get(mutation, state, store)
        break
      case mutation.type.indexOf('_COMMIT') :
        apiCommit(mutation, state, store)
        break
      case mutation.type.indexOf('_DELETE') :
        api_delete(mutation, state, store)
        break
    }
  }
}
export default [apiMiddleware]
