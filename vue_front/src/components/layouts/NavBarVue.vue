<template>
  <div>
    <nav v-bind:class="cor || 'blue'">
      <div class="nav-wrapper container">
        <a v-bind:href="url || '#'" class="brand-logo center">{{logo || 'Site'}}</a>
        <ul id="nav-mobile" class="left hide-on-med-and-down">
          <li><a href="sass.html">Sass</a></li>
          <li><a href="badges.html">Components</a></li>
          <li><a href="collapsible.html">JavaScript</a></li>
        </ul>
      </div>
    </nav>
  </div>
</template>
<script>
export default {
  name: 'NavBarVue',
  props: ['logo', 'url', 'cor']
}
</script>

<style>

</style>
