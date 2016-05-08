<template>
  <nav class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <div vlass="navbar-brand">
          <a v-link="{ path: '/'}">
            <img src="../assets/logo.png" class="navbar-brand"/>
          </a>
        </div>
      </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse">
        <ul class="menu nav navbar-nav">
          <li><a v-link="{ path: '/triage'}"><span>Triages</span></a></li>
          <li><a v-link="{ path: '/messages'}"><span>Messages</span></a></li>
          <li>
            <a class="dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Appointments <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
              <li>
                <a v-link="{ path: '/sites'}">Manage Sites</a>
              </li>
              <li class="seperator"></li>
              <li v-for="site in sites">
                <a v-link="{ path: '/appointments', params: {site_id: site.id}}">{{site.title}}</a>
              </li>
            </ul>
          </li>
          <li><a v-link="{ path: '/clients'}"><span>Clients</span></a></li>
          <li><a id="oc_link" href="oc.php"><span>On Call Info</span></a></li>
          <li><a id="sndlink" href="miapi.php"><span>Send Message</span></a></li>
          <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
              Admin <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
              <li><a v-link="{ path: '/bye'}"><span>Account Info</span></a></li>
              <li><a id="apptS_link" href="apptSched.php"><span>Appt Admin</span></a></li>
              <li><a id="contactlink" href="contact.php"><span>Contact Info</span></a></li>
              <li><a id="usrlink" href="users.php"><span>User Control</span></a></li>
            </ul>
         </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li><span class="navbar-text"></span></li>
          <li><p class="navbar-text">Welcome, {{user.name}}</p></li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>
</template>
<script>
  import Resources from '../store/resources'
  export default {
    props: {
      user: Object
    },
    name: 'Nav',
    store: Resources,
    vuex: {
    },
    init: function () {
      if (!this.loaded) {
        //
      }
    }
  }
</script>
<style>
</style>
