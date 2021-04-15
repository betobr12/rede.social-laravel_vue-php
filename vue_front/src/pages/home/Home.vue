<template>
  <span>
    <div class="row">
      <grid-vue class="input-field" tamanho="12">
        <textarea id="textarea1" class="materialize-textarea"></textarea>
        <label for="textarea1">O que está acontecendo?</label>
      </grid-vue>
      <p>
        <grid-vue tamanho="2 offset-s10" class="waves-effect waves-light btn">Publicar</grid-vue><!-- Botao -->
      </p>
    </div>
    <card-conteudo-vue perfil="http://materializecss.com/images/yuna.jpg" nome="Rodrigo Teste" data="17/02/2021 - 16:55">
      <card-detalhe-vue img="https://materializecss.com/images/sample-1.jpg" titulo="Imagem Nova" txt="Valores aleatorios">
      </card-detalhe-vue>
    </card-conteudo-vue>
  </span>
</template>

<script>
import CardConteudoVue  from '@/components/social/CardConteudoVue'
import CardDetalheVue from '@/components/social/CardDetalheVue'
import GridVue from '@/components/layouts/GridVue'

export default {
  name: 'Home',
  data () {
    return {
    }
  },
  components: {
    CardConteudoVue,
    CardDetalheVue,
    GridVue
  }
}
</script>
<!-- Add " CardConteudoVuescoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
