<template>
  <login-template>

    <span slot="menuesquerdo">
        <img src="https://www.frontiersin.org/image/journal/1606/thumbnail" class="responsive-img">
    </span>

    <span slot="principal">
      <span v-if="!cadastro">
        <h2>Login</h2>
        <input type="text" placeholder="E-mail" value="">
        <input type="password" placeholder="Senha" value="">
        <button class="btn">Entrar</button>
        <button class="btn orange" v-on:click="cadastro = !cadastro">CADASTRE-SE</button>
      </span>
      <span v-if="cadastro">
        <h2>Cadastre-se</h2>
        <input type="text" placeholder="nome" value="">
        <input type="text" placeholder="E-mail" value="">
        <input type="password" placeholder="Senha" value="">
        <input type="password" placeholder="Confirme sua Senha" value="">
        <button type="button" class="btn">Enviar</button>
        <button class="btn orange" v-on:click="cadastro = !cadastro">JÁ TENHO UMA CONTA</button>
      </span>
    </span>

  </login-template>
</template>

<script>

import LoginTemplate from '@/templates/LoginTemplate'
export default {
  name: 'Cadastro',
  data () {
    return {
      cadastro: true,
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
