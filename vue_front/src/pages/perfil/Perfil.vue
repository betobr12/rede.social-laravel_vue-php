<template>
  <site-template>

    <span slot="menuesquerdo">
        <img src="https://www.frontiersin.org/image/journal/1606/thumbnail" class="responsive-img">
    </span>

    <span slot="principal">
        <h2>Perfil</h2>
        <input type="text" placeholder="Nome" v-model="name">
        <input type="text" placeholder="E-mail" v-model="email">
        <div class="file-field input-field">
          <div class="btn">
            <span>Imagem</span>
            <input type="file">
          </div>
        <div class="file-path-wrapper">
          <input class="file-path validate" type="text">
        </div>
        </div>
        <input type="password" placeholder="Senha" v-model="password">
        <input type="password" placeholder="Confirme sua Senha" v-model="password_confirmation">
        <button type="button" class="btn" v-on:click="profile()">Atualizar</button>
    </span>

  </site-template>
</template>

<script>

import SiteTemplate from '@/templates/SiteTemplate'
import axios from 'axios'
export default {
  name: 'Perfil',
  data() {
    return {
      usuario:false,
      name: '',
      email: '',
      password: '',
      password_confirmation: ''
    }
  },
  created(){
    let usuarioAux = sessionStorage.getItem('usuario') // para resgatar os valores da sessao criados no login.vue
    if(usuarioAux){
      this.usuario = JSON.parse(usuarioAux);
      this.name = this.usuario.user.name; // mostrando dados que estão na sessao
      this.email = this.usuario.user.email;

    }
  },
  methods: {
    sair(){
      sessionStorage.clear(); //limpar a sessão
      this.usuario = false
    }
  },
  components:{
    SiteTemplate
  },
  methods:{
    profile(){
      console.log("ok");
      axios.post('http://127.0.0.1:8000/api/perfil', {
        name: this.name,
        email: this.email,
        password:this.password,
        password_confirmation:this.password_confirmation
      })
      .then(response => {
        console.log(response)
        if(response.data.success){
          // login com sucesso
          console.log('Cadastro realizado com sucesso')
          sessionStorage.setItem('usuario',JSON.stringify(response.data))
          this.$router.push('/')
        }else if(response.data.status == false){
          //Erro no cadastro
          alert('Falha ao cadastrar! Tente mais tarde');
        }else{
          // erros de validação
          console.log('erros de validação')
          let erros ='';
          for(let erro of Object.values(response.data.error)){
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
