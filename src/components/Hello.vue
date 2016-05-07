<template>
  <div class="hello">
    <h1 class="primary">{{ msg }}</h1>
    <div class="btn-group">
      <button class="btn btn-primary">primary</button>
      <button class="btn btn-success">success</button>
      <button class="btn btn-warn">warn</button>
      <button class="btn btn-danger">danger</button>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      // note: changing this line won't causes changes
      // with hot-reload because the reloaded component
      // preserves its current state and we are modifying
      // its initial state.
      msg: 'Hello World!'
    }
  }
}
</script>
