<template>
  <site-template>
    <span slot="menuesquerdo">
      <div class="row valign-wrapper">
        <grid-vue tamanho="4">
          <img :src="user.url" :alt="user.name" class="circle responsive-img"> <!-- notice the "circle" class -->
        </grid-vue>
        <grid-vue tamanho="8">
          <span class="black-text">
            <h5>{{ user.name }}</h5>
            {{ user.description }}
          </span>
        </grid-vue>
      </div>
    </span>

    <span slot="principal">
      <publicar-conteudo-vue :user="user" /> <!-- Passando usuario por um propos para o publicar-->
      <card-conteudo-vue
        perfil="http://materializecss.com/images/yuna.jpg"
        nome="Maria Silva"
        data="13/01/18 13:30">
          <card-detalhe-vue
            img="http://materializecss.com/images/sample-1.jpg"
            titulo=""
            txt="I am a very simple card. I am good at containing small bits of information.
            I am convenient because I require little markup to use effectively."  />
      </card-conteudo-vue>
    </span>


  </site-template>
</template>

<script>
import CardConteudoVue from '@/components/social/CardConteudoVue'
import CardDetalheVue from '@/components/social/CardDetalheVue'
import PublicarConteudoVue from '@/components/social/PublicarConteudoVue'
import SiteTemplate from '@/templates/SiteTemplate'
import GridVue from '@/components/layouts/GridVue'

export default {
  name: 'Home',
  data () {
    return {
      user: false,

    }
  },
  created() {
    let usuarioAux = sessionStorage.getItem('user') // para resgatar os valores da sessao criados no login.vue
    if(usuarioAux){
      this.user = JSON.parse(usuarioAux);
      this.name = this.user.name; // mostrando dados que estão na sessao
      this.url = this.user.url; // mostrando dados que estão na sessao
      this.description = this.user.description;
    }
  },
  components:{
    CardConteudoVue,
    CardDetalheVue,
    PublicarConteudoVue,
    SiteTemplate,
    GridVue
  }
}
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
