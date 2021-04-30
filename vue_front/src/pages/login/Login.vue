<template>

  <login-template>

    <span slot="menuesquerdo">
      <img src="https://www.designerd.com.br/wp-content/uploads/2013/06/criar-rede-social.png" class="responsive-img">
    </span>

    <span slot="principal">

      <h2>Login</h2>

      <input type="text" placeholder="E-mail" v-model="email">
      <input type="password" placeholder="Senha" v-model="password">
      <button class="btn" v-on:click="login()">Entrar</button>
      <router-link class="btn orange" to="/cadastro">Cadastre-se</router-link>

    </span>



  </login-template>



</template>

<script>
import LoginTemplate from '@/templates/LoginTemplate'
import axios from 'axios';


export default {
  name: 'Login',
  data () {
    return {
      email:'',
      password:''
    }
  },
  components:{
    LoginTemplate
  },
  methods:{
    login(){
      console.log("ok");
      axios.post(`http://127.0.0.1:8000/api/login`, {
        email: this.email,
        password:this.password
      })
      .then(response => {
        console.log(response)
        if(response.data.data.token){
          // login com sucesso
          console.log('login com sucesso')
        }else if(response.data.status == false){
          //login não existe
          console.log('login não existe')
        }else{
          // erros de validação
          console.log('erros de validação')
        }
      })
      .catch(e => {
        console.log(e)
      })
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
