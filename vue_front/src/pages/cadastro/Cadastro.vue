<template>
  <login-template>

    <span slot="menuesquerdo">
        <img src="https://www.frontiersin.org/image/journal/1606/thumbnail" class="responsive-img">
    </span>

    <span slot="principal">
        <h2>Cadastre-se</h2>
        <input type="text" placeholder="nome" value="">
        <input type="text" placeholder="E-mail" value="">
        <input type="password" placeholder="Senha" value="">
        <input type="password" placeholder="Confirme sua Senha" value="">
        <button type="button" class="btn">Enviar</button>
        <router-link  class="btn orange" to="/login">JÁ TENHO UMA CONTA</router-link >
    </span>

  </login-template>
</template>

<script>

import LoginTemplate from '@/templates/LoginTemplate'
export default {
  name: 'Cadastro',
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
