<template>
  <span>
    <header>
      <nav-bar-vue logo="Social" url="/" cor="blue-grey">
          <li><router-link to="/">Home</router-link></li>
          <li v-if="!user"><router-link to="/login">Entrar</router-link></li>
          <li v-if="!user"><router-link to="/cadastro">Cadastre-se</router-link></li>
          <li v-if="user"><router-link to="/perfil">{{user.name}}</router-link></li>
          <li v-if="user"><a v-on:click="sair()">Sair</a></li>
      </nav-bar-vue>
    </header>
    <main>
      <div class="container">
        <div class="row">
          <grid-vue>
            <slot name="principal"/>
            <!-- Conteudo -->
          </grid-vue>
        </div>
      </div>
    </main>
    <footer-vue cor="blue-grey" logo="Social" descricao="Teste de descrição" ano="2021" >
    </footer-vue>
  </span>
</template>
<script>
import NavBarVue  from '@/components/layouts/NavBarVue'
import FooterVue from '@/components/layouts/FooterVue'
import GridVue from '@/components/layouts/GridVue'
import CardMenuVue from '@/components/layouts/CardMenuVue'

export default {
  name: 'PostTemplate',
  data() {
    return {
      user: false
    }
  },
  components: {
    NavBarVue,
    FooterVue,
    GridVue,
    CardMenuVue
  },
  created() {
    console.log('created()')
    let usuarioAux = this.$store.getters.getUsuario;
    if (usuarioAux) {
      this.user = this.$store.getters.getUsuario;
    } else {
      this.$router.push('/login')
    }
  },
  methods: {
    sair() {
      this.$store.commit('setUsuario',null);
      sessionStorage.clear(); 
      this.user = false
      this.$router.push('/login')
    }
  },
}
</script>
<style>
</style>
