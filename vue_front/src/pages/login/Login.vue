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
export default {
  name: 'Login',
  data () {
    return {
      email:'',
      password:''
    }
  },
  components: {
    LoginTemplate
  },
  methods: {
    login() {
      console.log("ok");
      this.$http.post(this.$urlAPI+'login', {
        email: this.email,
        password:this.password
      })
      .then(response => {
        console.log(response)
        if(response.data.success) {
          // login com sucesso
          console.log('login com sucesso');
          this.$store.commit('setUsuario',response.data.user);
          sessionStorage.setItem('user',JSON.stringify(response.data.user));
          this.$router.push('/')
        } else if (response.data.status == false) {
          //login não existe
          console.log('login não existe')
          alert('Login invalido!');
        } else {
          // erros de validação
          console.log('erros de validação')
          let erros ='';
          for (let erro of Object.values(response.data.error)) {
            erros += erro +" ";
          }
          alert(erros);
        }
      })
      .catch(e => {
        console.log(e)
        alert("Falha! Tente novamente mais tarde")
      })
    }
  }
}
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>
