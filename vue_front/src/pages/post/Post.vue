<template>
  <post-template>
    <span slot="principal">
      <!-- Projeto Visualizar Post-->
    </span>
  </post-template>
</template>

<script>
import CardConteudoVue from '@/components/social/CardConteudoVue'
import CardDetalheVue from '@/components/social/CardDetalheVue'

import PostTemplate from '@/templates/PostTemplate'
import GridVue from '@/components/layouts/GridVue'

export default {
  name: 'Post',
  data () {
    return {}
  },

  components:{
    CardConteudoVue,
    CardDetalheVue,
    PostTemplate,
    GridVue
  },
}
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
