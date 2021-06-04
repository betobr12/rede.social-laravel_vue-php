<template>
  <div class="row">
    <grid-vue class="input-field" tamanho="12">
      <input type="text" v-model="content.title">
      <textarea v-if="content.title" v-model="content.description" class="materialize-textarea" placeholder="Conteudo"></textarea>
      <input type="text" v-if="content.title && content.description" placeholder="Link" v-model="content.link">
        <div class="file-field input-field" v-if="content.title && content.description">
          <div class="btn">
            <span>Imagem</span>
            <input type="file" v-on:change="salvaImagem">
          </div>
        <div class="file-path-wrapper">
          <input class="file-path validate" type="text" >
        </div>
        </div>
      <label>O que está acontecendo?</label>
    </grid-vue>
    <p class="right-align">
      <button v-on:click="addContent()" v-if="content.title && content.description" class="btn waves-effect waves-light" >Publicar</button>
    </p>
  </div>
</template>

<script>
import GridVue from '@/components/layouts/GridVue'

export default {
  name: 'PublicarConteudoVue',
  props:[],
  data () {
    return {
      content: {
        title: '',
        description: '',
        link: '',
        image: null,
        url_image: null,
      }
    }
  },
  components: {
    GridVue
  },
  methods: {
    salvaImagem(e){
      let arquivo = e.target.files || e.dataTransfer.files;
      if(!arquivo.length){
        return;
      }
      let reader = new FileReader();
      reader.onloadend = (e) => {
        this.content.image = e.target.result;
      };
      reader.readAsDataURL(arquivo[0]);
    },
    addContent() {
      console.log(this.content);
      this.$http.post(this.$urlAPI+'content',{
        title: this.content.title,
        description: this.content.description,
        link: this.content.link,
        image: this.content.image,
      },{"headers": {
        "authorization":"Bearer "+this.$store.getters.getToken//recuperar token do usuario
        }
      }).then(response => {
        if (response.data.success) {
          console.log(response.data.content.data);
          this.content = {title: '', description: '', link: '', image: '' }; //limpa as variaveis
          this.$store.commit('setContentsTimeLine',response.data.content.content.data)
          alert(response.data.success);
        } else {
          alert(response.data.error);
        }
      }).catch(e => {
        console.log(e)
        alert('Erro! Tente novamente mais tarde!')
        }
      )
    },
  },
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
