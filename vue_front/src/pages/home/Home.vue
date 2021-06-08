<template>
  <site-template>
    <span slot="menuesquerdo">
      <div class="row valign-wrapper">
        <grid-vue tamanho="4">
          <router-link :to="'/pagina/'+user.id+'/'+$slug(user.name,{lower: true})">
           <img :src="user.url" :alt="user.name" class="circle responsive-img"> <!-- notice the "circle" class -->
          </router-link>
        </grid-vue>
        <grid-vue tamanho="8">
          <span class="black-text">
            <router-link :to="'/pagina/'+user.id+'/'+$slug(user.name,{lower: true})">
             <h5>{{ user.name }}</h5>
            </router-link>
            {{ user.description }}
          </span>
        </grid-vue>
      </div>
    </span>
    //--teste = :current_page="current_page"
    <span slot="menuesquerdoamigos">
      <span><i class="medium material-icons">people</i></span>

      <li v-for="item in friends" :key="item.id" >{{ item.name }}</li>
      <li v-if="!friends.length">Nenhum amigo</li>

    </span>
    <span slot="principal">
      <publicar-conteudo-vue />
      <card-conteudo-vue v-for="item in listaConteudos" :key="item.id"
        :content_id="item.id"

        :liked_content="item.liked_content"
        :total_likes="item.total_likes"
        :comments="item.comments"
        :profile="item.user_url"
        :name="item.user_name"
        :user_id="item.user_id"
        :data="item.created_at">
          <card-detalhe-vue
            :url_image="item.url_image"
            :content_id="item.id"
            :title="item.title"
            :description="item.description"
            :link="item.link" />
      </card-conteudo-vue>
     <button v-if="urlNextPage" @click="pageLoad()" class="btn blue">Mais... </button>
        <!--<div v-scroll="handleScroll"></div>-->
    </span>
  </site-template>
</template>

<script>
import CardConteudoVue from '@/components/social/CardConteudoVue'
import CardDetalheVue from '@/components/social/CardDetalheVue'
import PublicarConteudoVue from '@/components/social/PublicarConteudoVue'
import SiteTemplate from '@/templates/SiteTemplate'
import GridVue from '@/components/layouts/GridVue'

export default {
  name: 'Home',
  data () {
    return {
      user: { name: '', url: '', description: '',
      },
      urlNextPage: null,
      stopScroll: false,
      friends:[],

    }
  },
  created() {
    let usuarioAux = this.$store.getters.getUsuario; // para resgatar os valores da sessao criados no login.vue

    if(usuarioAux){
      this.user = this.$store.getters.getUsuario;
      this.$http.get(this.$urlAPI+'content',{"headers":{"authorization":"Bearer "+this.$store.getters.getToken}})
      .then(response => {

        //this.current_page = response.data.content.current_page ?response.data.content.current_page : 1 ; //passa o numero da pagina atual via props

        if (response.data.content)
        {
          console.log(response.data.content);
          this.$store.commit('setContentsTimeLine',response.data.content.data);
          this.urlNextPage = response.data.content.next_page_url;
        }
      })
      .catch(e => {
        console.log(e)
        alert('Erro! Tente novamente mais tarde!')
      })
      /*-----------------------------LISTAR OS AMIGOS DO USUARIO---------------------*/
      this.$http.get(this.$urlAPI+'user/list_friend',{"headers":{"authorization":"Bearer "+this.$store.getters.getToken}})
      .then(response => {
        if (response.data.success) {

         this.friends = response.data.friends;

        } else {
          alert(response.data.error);
        }
      })
      .catch(e => {
        console.log(e)
        alert('Erro! Tente novamente mais tarde!')
      })

    }
  },
  components:{
    CardConteudoVue,
    CardDetalheVue,
    PublicarConteudoVue,
    SiteTemplate,
    GridVue
  },
  methods: {

  /*  handleScroll() {
      //console.log(document.body.clientHeight);//mostra a altura da tela
     // console.log(window.scrollY); //percorre a pagina no momento em que movimentamos
      if (this.stopScroll) {
        return;
      }
      //this.total = window.scrollY - document.body.clientHeight;
      if (window.scrollY >= document.body.clientHeight - 949) {
        this.stopScroll = true;
        // stopScroll evita duplicar o conteudo da pagina, a cada pagina mostrada uma nova chave é gerada,
         //se não declaramos essa variavel, no fim da paginação gera uma duplicidade infinita --
        this.pageLoad();
      }

    },
    */
    pageLoad() {
      if (!this.urlNextPage) {
        return;
      }
      this.$http.get(this.urlNextPage,{"headers":{"authorization":"Bearer "+this.$store.getters.getToken}})
      .then(response => {
       console.log(response.data.content);

       // this.current_page = response.data.content.current_page; //passa o numero da pagina atual via props

        if (response.data.content && this.$route.name == "Home") {

          this.$store.commit('setPaginationContentsTimeLine',response.data.content.data);
          this.urlNextPage = response.data.content.next_page_url;
          this.stopScroll = false;
        }
      })
      .catch(e => {
        console.log(e)
        alert('Erro! Tente novamente mais tarde!')
      })

    },
  },

  computed: {
    listaConteudos() {
     return this.$store.getters.getContentsTimeLine;
    }
  }
}
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
