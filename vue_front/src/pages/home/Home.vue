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
      <router-link v-for="item in friends" :key="item.id" :to="'/pagina/'+item.id+'/'+$slug(item.name,{lower: true})">
        <li  >{{ item.name }}</li>
      </router-link>
      <li v-if="!friends.length">Nenhum amigo</li>

      <span><i class="medium material-icons">fast_forward</i></span>
      <router-link v-for="item in followers" :key="item.id" :to="'/pagina/'+item.id+'/'+$slug(item.name,{lower: true})">
        <li  >{{ item.name }}</li>
      </router-link>
      <li v-if="!followers.length">Nenhum amigo</li>

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
      followers:[]
    }
  },
  
  created() {
    this.updatePage();
  },

  components:{
    CardConteudoVue,
    CardDetalheVue,
    PublicarConteudoVue,
    SiteTemplate,
    GridVue
  },

  watch:{
    '$route':'updatePage',
  },

  methods: {
    updatePage() {
      let usuarioAux = this.$store.getters.getUsuario;
      if(usuarioAux){
        this.user = this.$store.getters.getUsuario;
        this.$http.get(this.$urlAPI+'content',{"headers":{"authorization":"Bearer "+this.$store.getters.getToken}})
        .then(response => {          
          if (response.data.content)
          {          
            this.$store.commit('setContentsTimeLine',response.data.content.data);
            this.urlNextPage = response.data.content.next_page_url;
          }
        })
        .catch(e => {         
          alert('Erro! Tente novamente mais tarde!')
        })
        /*-----------------------------LISTAR OS AMIGOS DO USUARIO---------------------*/
        this.$http.get(this.$urlAPI+'user/list_friend',{"headers":{"authorization":"Bearer "+this.$store.getters.getToken}})
        .then(response => {
          if (response.data.success) {
           this.friends   = response.data.friends;
           this.followers = response.data.followers;
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
    pageLoad() {
      if (!this.urlNextPage) {
        return;
      }
      this.$http.get(this.urlNextPage,{"headers":{"authorization":"Bearer "+this.$store.getters.getToken}})
      .then(response => {         
        if (response.data.content && this.$route.name == "Home") {
          this.$store.commit('setPaginationContentsTimeLine',response.data.content.data);
          this.urlNextPage = response.data.content.next_page_url;
          this.stopScroll = false;
        }
      })
      .catch(e => {        
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
