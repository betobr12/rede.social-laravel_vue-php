<template>
  <login-template>
    <span slot="menuesquerdo">
        <img src="https://www.frontiersin.org/image/journal/1606/thumbnail" class="responsive-img">
    </span>
    <span slot="principal">
        <h2>Cadastre-se</h2>
        <input type="text" placeholder="Nome" v-model="name">
        <input type="text" placeholder="E-mail" v-model="email">
        <input type="password" placeholder="Senha" v-model="password">
        <input type="password" placeholder="Confirme sua Senha" v-model="password_confirmation">
        <button type="button" class="btn" v-on:click="register()">Enviar</button>
        <router-link  class="btn orange" to="/login">JÁ TENHO UMA CONTA</router-link >
    </span>
  </login-template>
</template>
<script>
import LoginTemplate from '@/templates/LoginTemplate'
export default {
  name: 'Cadastro',
  data() {
    return {
      name: '',
      email: '',
      password: '',
      password_confirmation: ''
    }
  },
  components: {
    LoginTemplate
  },
  methods: {
    register() {
      console.log("ok");
      this.$http.post(this.$urlAPI+'register', {
        name: this.name,
        email: this.email,
        password: this.password,
        password_confirmation: this.password_confirmation
      })
      .then(response => {
        console.log(response)
        if (response.data.success) {
          // login com sucesso
          console.log('Cadastro realizado com sucesso')
          this.$store.commit('setUsuario',response.data.user);
          sessionStorage.setItem('user',JSON.stringify(response.data.user))
          this.$router.push('/')
        } else if (response.data.status == false){
          //Erro no cadastro
          alert('Falha ao cadastrar! Tente mais tarde');
        } else {
          // erros de validação
          console.log('erros de validação')
          let erros ='';
          for (let erro of Object.values(response.data.error)) {
            erros += erro +"";
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
