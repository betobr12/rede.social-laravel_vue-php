<template>
  <div>
    <nav v-bind:class="cor || 'blue'">
      <div class="nav-wrapper container">
        <router-link v-bind:to="url || '/'" class="brand-logo center">{{logo || 'Site'}}</router-link>
        <ul id="nav-mobile" class="left hide-on-med-and-down">
          <slot />
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
