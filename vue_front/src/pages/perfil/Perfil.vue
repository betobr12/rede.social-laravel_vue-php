<template>
  <site-template>
    <span slot="menuesquerdo">
        <img :src="user.url" class="responsive-img">
    </span>
    <span slot="principal">
        <h2>Perfil</h2>
        <input type="text" placeholder="Nome" v-model="name">
        <input type="text" placeholder="E-mail" v-model="email">
        <div class="file-field input-field">
          <div class="btn">
            <span>Imagem</span>
            <input type="file" v-on:change="salvaImagem">
          </div>
        <div class="file-path-wrapper">
          <input class="file-path validate" type="text">
        </div>
        </div>
        <input type="text" placeholder="Descrição do perfil (opcional)" v-model="description">
        <input type="password" placeholder="Senha" v-model="password">
        <input type="password" placeholder="Confirme sua Senha" v-model="password_confirmation">
        <button type="button" class="btn" v-on:click="profile()">Atualizar</button>
    </span>
  </site-template>
</template>

<script>
import SiteTemplate from '@/templates/SiteTemplate';


export default {
  name: 'Perfil',
  data () {
    return {
      user:false,
      name:'',
      email:'',
      password:'',
      password_confirmation:'',
      imagem:'',
      description:''
    }
  },

  created() {
    // para resgatar os valores da sessao criados no login.vue
    let usuarioAux = this.$store.getters.getUsuario; // para resgatar os valores da sessao criados no login.vue
    if(usuarioAux){
      this.user = this.$store.getters.getUsuario;
      this.name = this.user.name; // mostrando dados que estão na sessao
      this.email = this.user.email;
      this.description = this.user.description;
    }
  },

  components: {
    SiteTemplate
  },

  methods: {
    sair() {
      sessionStorage.clear(); //limpar a sessão
      this.user = false
    },

    /*
      salvaImagem(e) {
     // var preview = document.querySelector('img');
      var file    = document.querySelector('input[type=file]').files[0];
      var reader  = new FileReader();

      reader.onloadend = (e) => {
       // preview.src = reader.result;
       this.imagem = reader.result;
      }

      if (file) {
        reader.readAsDataURL(file);
      } else {
      //  preview.src = "";
      }
      console.log(this.imagem);
    },
    */

    salvaImagem(e){
      let arquivo = e.target.files || e.dataTransfer.files;
      if(!arquivo.length){
        return;
      }
      let reader = new FileReader();
      reader.onloadend = (e) => {
        this.imagem = e.target.result;
      };
      reader.readAsDataURL(arquivo[0]);
    },

    profile() {
      this.$http.put(this.$urlAPI+'profile', {
        name: this.name,
        email: this.email,
        imagem: this.imagem,
        description: this.description,
        password: this.password,
        password_confirmation: this.password_confirmation
      },{"headers":{"authorization":"Bearer "+this.$store.getters.getToken}})
      .then(response => {
          //console.log(response)
          if (response.data.success){
            // login com sucesso
            console.log(response.data.user);
            this.user = response.data.user;
            this.$store.commit('setUsuario',response.data.user);
            sessionStorage.setItem('user',JSON.stringify(response.data.user))
            alert('Perfil atualizado!');
          } else {
            // erros de validação
            console.log('erros de validação')
            let erros = '';
            for (let erro of Object.values(response.data.error)) {
              erros += erro +" ";
            }
            alert(erros);
          }
        }
      )
      .catch(e => {
        console.log(e)
        alert('Erro! Tente novamente mais tarde!')
        }
      )
    }

  }
}
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
