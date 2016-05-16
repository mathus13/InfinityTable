<template>
    <div>
      <form v-if="showform">
        <div class="form-group col-md-6">
          <label>Title</label>
          <input v-model="edit_session.title" class="form-control"/>
        </div>
        <div class="form-group col-md-6">
          <label>Date/Time</label>
          <input v-model="edit_session.date" class="form-control"/>
        </div>
        <div class="form-group col-md-12">
          <label>Description</label>
          <textarea v-model="edit_session.description" class="form-control"></textarea>
        </div>
        <input v-if="edit_session.id" type="hidden" name="id" value="{{edit_session.id}}"/>
        <div class="form-group btn-group col-md-12">
          <button class="btn btn-primary" type="button" v-on:click="commit_session">
            Save
          </button>
          <button class="btn btn-default" type="button" v-on:click="reset_edit">
            Reset
          </button>
          <button class="btn btn-default" type="button" v-on:click="cancel_edit">
            Cancel
          </button>
        </div>
      </form>
      <div v-else>
        <div class="btn-group pull-right">
          <button class="btn btn-default btn-sm" v-on:click="editSession">
            <span class="glyphicon glyphicon-pencil"></span>
          </button>
          <button class="btn btn-danger btn-sm" v-on:click="deleteSession">
            <span class="glyphicon glyphicon-trash"></span>
          </button>
        </div>
        <h2>{{session.title}}</h2>
        <p>{{session.description}}</p>
      </div>
    </div>
</template>

<script>
import {sessionsCommit} from '../store/actions.js'
import {sessionsDelete} from '../store/actions.js'
export default {
  props: ['session', 'showform'],
  data () {
    return {
      edit_session: {}
    }
  },
  methods: {
    reset: function () {
      this.session = {}
    },
    cancel: function () {
      this.showform = false
      this.$dispatch('appt_session_edit_cancel', this.session)
    },
    commit_session: function () {
      sessionsCommit(this.edit_session)
      this.showform = false
      this.$dispatch('appt_session_commit', this.edit_session)
    },
    deleteSession: function () {
      sessionsDelete(this.session)
      this.$dispatch('appt_session_commit', {})
    },
    editSession: function () {
      this.showform = true
    },
    cancel_edit: function () {
      this.showform = false
      this.mock_session(this.session)
    },
    reset_edit: function () {
      this.mock_session(this.session)
    },
    mock_session: function (session) {
      var edit_session = {}
      edit_session.title = session.title
      edit_session.description = session.description
      edit_session.id = session.id
      this.edit_session = edit_session
    }
  },
  events: {
    'sessions.request.load': function (session) {
      this.session = session
      this.mock_session(this.session)
      this.showform = false
    }
  }
}
</script>
