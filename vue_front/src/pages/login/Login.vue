<template>
  <login-template>

    <span slot="menuesquerdo">
        <img src="https://www.frontiersin.org/image/journal/1606/thumbnail" class="responsive-img">
    </span>

    <span slot="principal">
        <h2>Login</h2>
        <input type="text" placeholder="E-mail" value="">
        <input type="password" placeholder="Senha" value="">
        <button class="btn">Entrar</button>
        <router-link class="btn orange" to="/cadastro">CADASTRE-SE</router-link>
    </span>

  </login-template>
</template>

<script>

import LoginTemplate from '@/templates/LoginTemplate'
export default {
  name: 'Login',
  data () {
    return {

    }
  },
  components:{
    LoginTemplate
  }
}
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
