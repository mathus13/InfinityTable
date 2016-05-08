import Vue from 'vue'
import Vuex from 'vuex'
import VueResource from 'vue-resource'
import actions from './actions.js'
Vue.use(Vuex)
Vue.use(VueResource)

var site
var method = 'POST'
var prefix

var apiCommit = function (mutation, state, store) {
  var url = 'http://clients.local/api/'
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
      case 201:
        store.dispatch(prefix + '_COMMIT_RESPONSE', response.data.data)
        break
    }
  })
}

var apiGet = function (mutation, state, store) {
  var url = 'http://clients.local/api/'
  var resource = mutation.payload[0]
  url += resource
  prefix = resource.toUpperCase()
  Vue.http({url: url, method: 'GET'}).then(function (response) {
    var items = []
    switch (response.status) {
      case 200:
        var apiReturn = response.data
        for (let site of apiReturn.data) {
          if (site.hasOwnProperty('id')) {
            items.push(site.attributes)
          }
        }
        break
    }
    switch (prefix) {
      case 'CLIENTS':
        actions.clientsSet(items)
        break
      case 'APPOINTMENTS':
        actions.appoitnmentsSet(items)
        break
      case 'SITES':
        actions.sitesSet(items)
        break
    }
  })
}

var apiDelete = function (mutation, state, store) {
  var url = 'http://clients.local/api/'
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
//    apiGet(state)
  },
  onMutation (mutation, state, store) {
    switch (mutation.type) {
      case 'CLIENTS_FETCH':
      case 'SITES_FETCH':
      case 'APPOINTMENTS_FETCH':
        apiGet(mutation, state, store)
        break
      case 'CLIENTS_COMMIT':
      case 'APPOINTMNETS_COMMIT':
      case 'SITES_COMMIT':
        apiCommit(mutation, state, store)
        break
      case 'CLIENTS_DELETE':
      case 'APPOINTMNETS_DELETE':
      case 'SITES_DELETE':
        apiDelete(mutation, state, store)
        break
    }
  }
}
export default [apiMiddleware]
