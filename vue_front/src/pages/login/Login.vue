<template>
  <login-template>

    <span slot="menuesquerdo">
        <img src="https://i-goo.com/content/auto_site_logo.png" class="responsive-img">
    </span>

    <span slot="principal">
      <h2>Login</h2>

      <input type="text" placeholder="E-mail" value="">
      <input type="password" placeholder="Senha" value="">
      <button type="button" class="btn">Entrar</button>
    </span>





  </login-template>
</template>

<script>
import CardConteudoVue from '@/components/social/CardConteudoVue'
import CardDetalheVue from '@/components/social/CardDetalheVue'
import PublicarConteudoVue from '@/components/social/PublicarConteudoVue'
import LoginTemplate from '@/templates/LoginTemplate'

export default {
  name: 'Login',
  data () {
    return {
    }
  },
  components:{
    CardConteudoVue,
    CardDetalheVue,
    PublicarConteudoVue,
    LoginTemplate
  }
}
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
