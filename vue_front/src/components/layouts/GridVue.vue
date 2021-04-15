<template>
  <div :class="'col s'+ tamanho">
  <slot></slot>
</div>

</template>
<script>
export default {
  name: 'GridVue',
  props: ['tamanho']
}
</script>

<style>

</style>
