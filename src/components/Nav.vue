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
          <li><a v-link="{ path: '/games'}"><span>Games</span></a></li>
          <li><a v-link="{ path: '/groups'}"><span>Messages</span></a></li>
          <li>
            <a class="dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Characters <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
              <li>
                <a v-link="{ path: '/characters'}">New Character</a>
              </li>
              <li class="seperator"></li>
              <li v-for="hero in heros">
                <a v-link="{ path: '/characters', params: {hero_id: hero.id}}">{{hero.name}}</a>
              </li>
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
//      user: Object
    },
    data () {
      return {
        heros: [],
        user: {

        }
      }
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
