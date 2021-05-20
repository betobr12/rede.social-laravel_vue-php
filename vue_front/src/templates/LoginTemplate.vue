<template>
  <span>
    <header>
      <nav-bar-vue logo="Social" url="/" cor="blue-grey">
          <li v-if="!user"><router-link to="/login">Entrar</router-link></li>
          <li v-if="!user"><router-link to="/cadastro">Cadastre-se</router-link></li>
          <li v-if="user"><router-link to="/perfil">{{user.name}}</router-link></li>
          <li v-if="user"><a v-on:click="sair()">Sair</a></li>
      </nav-bar-vue>
    </header>
    <main>
      <div class="container">
        <div class="row">
          <grid-vue tamanho="6">
            <card-menu-vue>
            <slot name="menuesquerdo" />
            </card-menu-vue>
          </grid-vue>
          <grid-vue tamanho="6">
            <slot name="principal" />
          </grid-vue>
        </div>
      </div>
    </main>
    <footer-vue cor="blue-grey" logo="Social" descricao="Teste de descrição" ano="2021" >
            <li><a class="grey-text text-lighten-3" href="#!">Home</a></li>
            <li><a class="grey-text text-lighten-3" href="#!">Link 2</a></li>
            <li><a class="grey-text text-lighten-3" href="#!">Link 3</a></li>
            <li><a class="grey-text text-lighten-3" href="#!">Link 4</a></li>
    </footer-vue>
  </span>
</template>
<script>
import NavBarVue  from '@/components/layouts/NavBarVue'
import FooterVue from '@/components/layouts/FooterVue'
import GridVue from '@/components/layouts/GridVue'
import CardMenuVue from '@/components/layouts/CardMenuVue'
export default {
  name: 'SiteTemplate',
  data(){
    return{
      user: false //variavel criada para o template
    }
  },
  components: {
    NavBarVue,
    FooterVue,
    GridVue,
    CardMenuVue
  },
  created(){
    console.log('created()');
    let usuarioAux = this.$store.getters.getUsuario; // para resgatar os valores da sessao criados no login.vue
    if(usuarioAux){
      this.user = this.$store.getters.getUsuario;
      this.$router.push('/')
    }
  }
}
</script>
<style>
</style>
