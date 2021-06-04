<template>
  <site-template>
    <span slot="menuesquerdo">
      <div class="row valign-wrapper">
        <grid-vue tamanho="4">
          <router-link :to="'/pagina/'+userPage.id+'/'+$slug(userPage.name,{lower: true})">
           <img :src="userPage.url" :alt="userPage.name" class="circle responsive-img"> <!-- notice the "circle" class -->
          </router-link>

        </grid-vue>
        <grid-vue tamanho="8">
          <span class="black-text">
            <router-link :to="'/pagina/'+userPage.id+'/'+$slug(userPage.name,{lower: true})">
              <h5>{{ userPage.name }}</h5>
            </router-link>
            {{ userPage.description }}
          </span>
        </grid-vue>
      </div>
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
            :title="item.title"
            :description="item.description"
            :link="item.link" />
      </card-conteudo-vue>
     <!-- <button v-if="urlNextPage" @click="pageLoad()" class="btn blue">Mais... </button> -->
      <div v-scroll="handleScroll"></div>
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
  name: 'Pagina',
  data () {
    return {
      user: false,
      urlNextPage: null,
      stopScroll: false,
      userPage: {
        name: '',
        url: '',
        description: ''
      }


    }
  },
  created() {
    let usuarioAux = this.$store.getters.getUsuario; // para resgatar os valores da sessao criados no login.vue
    if(usuarioAux){
      this.user = this.$store.getters.getUsuario;
      this.$http.get(this.$urlAPI+'content/page/'+this.$route.params.id,{"headers":{"authorization":"Bearer "+this.$store.getters.getToken}})
      .then(response => {
        //console.log(response.data.content);
        if (response.data.content) {
          console.log(response.data.content);
          this.$store.commit('setContentsTimeLine',response.data.content.data);
          this.urlNextPage = response.data.content.next_page_url;
          this.userPage = response.data.data_user_page;
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
    handleScroll() {
      //console.log(document.body.clientHeight);//mostra a altura da tela
     // console.log(window.scrollY); //percorre a pagina no momento em que movimentamos
      if (this.stopScroll) {
        return;
      }
      //this.total = window.scrollY - document.body.clientHeight;
      if (window.scrollY >= document.body.clientHeight - 949) {
        this.stopScroll = true;
        /*-- stopScroll evita duplicar o conteudo da pagina, a cada pagina mostrada uma nova chave é gerada,
         se não declaramos essa variavel, no fim da paginação gera uma duplicidade infinita --*/
        this.pageLoad();
      }

    },
    pageLoad() {
      if (!this.urlNextPage) {
        return;
      }
      this.$http.get(this.urlNextPage,{"headers":{"authorization":"Bearer "+this.$store.getters.getToken}})
      .then(response => {
        console.log(response.data.content);
        if (response.data.content && this.$route.name == "Pagina") {
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
