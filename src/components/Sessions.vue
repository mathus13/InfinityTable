<template>
  <div>
    <div class="col-md-3 list-group">
      <div class="list-group-item list-group-item-heading">
        <div class="pull-right btn-group btn-group-xs">
          <button class="btn btn-default btn-xs" v-on:click="addSession">
            <span class="glyphicon glyphicon-plus-sign"></span>
          </button>
          <button class="btn btn-default btn-xs" v-on:click="reloadSessions">
            <span class="glyphicon glyphicon-retweet"></span>
          </button>
        </div>
        <span class="h4 strong">Sessions</span>
      </div>
      <a v-for="Session in Sessions" class="list-group-item" v-on:click="loadSession(Session)">
        <span class="strong">{{Session.title}}</span>
      </a>
    </div>
    <session-view class="col-md-9" :session="currentSession" :showform="showForm"></session-view>
  </div>
</template>
<script>
  import Store from '../store/resources'
  import {sessionsFetch} from '../store/actions.js'
  import Session from './Session'
  export default {
    data () {
      return {
        currentSession: {},
        showForm: true
      }
    },
    store: Store,
    vuex: {
      getters: {
        Sessions: (state) => state.sessions.sessions,
        loaded: (state) => state.sessions.loaded
      }
    },
    methods: {

      loadSession: function (Session) {
        this.currentSession = Session
        this.showForm = false
        this.$broadcast('sessions.request.load', Session)
      },
      addSession: function () {
        this.currentSession = {}
        this.showForm = true
      },
      reloadSessions: function () {
        sessionsFetch()
      }
    },
    components: {
      'session-view': Session
    },
    init: function () {
      if (!this.loaded) {
        sessionsFetch()
      }
    },
    events: {
      'appt_session_commit': function (Session) {
        this.reloadSessions()
        this.loadSession(Session)
      }
    }
  }
</script>
