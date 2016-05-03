<template>
  <nav class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
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
      <div class="collapse navbar-collapse">
        <ul class="menu nav navbar-nav">
          <li><a v-link="{ path: '/sessions'}"><span>Sessions</span></a></li>
          <li><a v-link="{ path: '/groups'}"><span>Groups</span></a></li>
          <li>
            <a class="dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Characters <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
              <li>
                <a v-link="{ path: '/sites'}">* New Character</a>
              </li>
              <li class="seperator"></li>
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
export default {
  data () {
    return {
      user: {}
    }
  }
}
</script>